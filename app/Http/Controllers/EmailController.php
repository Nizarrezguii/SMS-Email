<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller

{
    public function fetchEmails()
{
    // Outlook IMAP settings
    $host = '{outlook.office365.com:993/imap/ssl}';
    $username = 'nizarrezguii@outlook.com';
    $password = 'fvnqjflepbyqjjij';

    // Connect to the Outlook IMAP server
    $inbox = imap_open($host, $username, $password);

    if (!$inbox) {
        throw new Exception('Failed to connect to Outlook IMAP server: ' . imap_last_error());
    }

    // Fetch all emails and sort them by date in descending order
    $emails = imap_search($inbox, 'ALL', SE_UID, 'SINCE "' . date('j F Y', strtotime("-30 days")) . '"');
    if (!is_array($emails)) {
        $emails = array(); // initialize $emails as an empty array
    }
    rsort($emails);
    // dd($emails);

    // Loop through the emails and fetch the headers and bodies
    foreach ($emails as $email) {
    $overview = imap_fetch_overview($inbox, $email, 0);
    if (empty($overview)) {
        continue;
    }
    $uid = $overview[0]->uid;
    $header = imap_fetchheader($inbox, $uid, FT_UID);
    $headerObj = imap_rfc822_parse_headers($header);
    $body = imap_fetchbody($inbox, $uid, 1, FT_UID | FT_PEEK);
    $subject = isset($headerObj->subject) ? $headerObj->subject : '';
    $from = isset($headerObj->from[0]) ? implode(' ', (array) $headerObj->from[0]) : '';
    $to = isset($headerObj->to[0]) ? implode(' ', (array) $headerObj->to[0]) : '';
    $date = isset($headerObj->date) ? date('Y-m-d H:i:s', strtotime($headerObj->date)) : '';
    $body = isset($body) ? htmlspecialchars(imap_utf8($body), ENT_QUOTES) : '';

    // Check if the email already exists in the database
    $existingEmail = DB::table('emails')->where('from', $from)->where('to', $to)->where('date', $date)->first();

    // Insert the email data into the database if it doesn't already exist
    if (!$existingEmail) {
        DB::table('emails')->insert([
            'subject' => $subject,
            'from' => $from,
            'to' => $to,
            'date' => $date,
            'body' => $body,
        ]);
    }
}

    // Fetch the newest emails next time
    $lastEmail = DB::table('emails')->orderBy('date', 'desc')->first();
    $newestEmailDate = $lastEmail ? date('j F Y', strtotime($lastEmail->date)) : date('j F Y', strtotime("-30 days"));
    $emails = imap_search($inbox, 'ALL', SE_UID, 'SINCE "' . $newestEmailDate . '"');
    if (!is_array($emails)) {
        $emails = array(); // initialize $emails as an empty array
    }
    rsort($emails);

    // Close the connection to the Outlook IMAP server
    imap_close($inbox);
    return redirect('/test');
}

    //this function return the emails from database to the view
    public function showEmails(Request $request)
{
    $search = $request->input('search');

        $emails = Email::when($search, function ($query) use ($search) {
                return $query->where('subject', 'LIKE', '%' . $search . '%');
            })
            ->paginate(8);
    return view('emails.test', compact('emails', 'search'));
}
}

