<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View;



class OutlookController extends Controller
{
    public function index(Request $request) {
        // Outlook IMAP settings
        $host = '{outlook.office365.com:993/imap/ssl}';
        $username = 'est.nizar@hotmail.fr';
        $password = 'psfqprrhxkvxtlwl';

        // Connect to the Outlook IMAP server
        $inbox = imap_open($host, $username, $password);

        if (!$inbox) {
            throw new Exception('Failed to connect to Outlook IMAP server: ' . imap_last_error());
        }

        // Define the fields to fetch from the messages
        $fields = 'SUBJECT FROM TO DATE';

        // Get the search term from the request
        $searchTerm = $request->input('search');

        // Create the search query
        $searchQuery = $searchTerm ? 'SUBJECT "'.$searchTerm.'" ' : '';

        // Search for all messages and sort them by date in descending order
        $emails = imap_search($inbox, $searchQuery.'ALL', SE_UID, 'SINCE "' . date('j F Y', strtotime("-30 days")) . '"');
        if (!is_array($emails)) {
            $emails = array(); // initialize $emails as an empty array
        }
        rsort($emails);

        // Define the number of messages to display per page
        $messagesPerPage = 10;

        // Calculate the current page number
        $currentPage = $request->input('page') ?? 1;

        // Calculate the offset of the first message to display on the current page
        $offset = ($currentPage - 1) * $messagesPerPage;

        // Fetch the headers and bodies for the selected page of messages
        $pageEmails = array_slice($emails, $offset, $messagesPerPage);
        $pageHeaders = imap_fetch_overview($inbox, implode(",", $pageEmails), FT_UID);
        $pageMessages = array_map(function ($header) use ($inbox) {
            $messageNumber = imap_msgno($inbox, $header->uid);
            $body = imap_fetchbody($inbox, $messageNumber, '1');
            $header->msgno = $messageNumber;
            $header->body = $body;
            return $header;
        }, $pageHeaders);



        // Create an array of message headers for the current page
        $pageMessages = array_map(function ($header) use ($fields) {
            $subject = isset($header->subject) ? $header->subject : '';
            $from = isset($header->from) ? $header->from : '';
            $to = isset($header->to) ? $header->to : '';
            $date = isset($header->date) ? date('Y-m-d H:i:s', strtotime($header->date)) : '';
            $body = isset($header->body) ? htmlspecialchars(imap_utf8($header->body), ENT_QUOTES) : '';
            $emailId = $header->msgno; // use the message number as the email ID

            return (object) compact('subject', 'from', 'to', 'date', 'body');
        }, $pageMessages);

        // Create a new LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
        $pageMessages,
        count($emails),
        $messagesPerPage,
        $currentPage,
        ['path' => $request->url(), 'query' => array_merge($request->query(), ['search' => $searchTerm])]
);

    // Filter the emails based on the search term
    if ($searchTerm) {
    $filteredEmails = [];
    foreach ($emails as $email) {
        $header = imap_headerinfo($inbox, $email);
        if (stripos($header->subject, $searchTerm) !== false || stripos($header->fromaddress, $searchTerm) !== false) {
            $filteredEmails[] = $email;
        }
    }
    $emails = $filteredEmails;
}

    // Get the email details for the selected page of emails
    $pageEmails = array_slice($emails, $offset, $messagesPerPage);
    $pageHeaders = imap_fetch_overview($inbox, implode(",", $pageEmails), FT_UID);
    $pageMessages = array_map(function ($header) use ($inbox) {
        $messageNumber = imap_msgno($inbox, $header->uid);
        $body = imap_fetchbody($inbox, $messageNumber, '1');
        $header->msgno = $messageNumber;
        $header->body = $body;
        return $header;
    }, $pageHeaders);

    // Format the email details for display
    $pageMessages = array_map(function ($header) use ($fields) {
    $subject = isset($header->subject) ? $header->subject : '';
    $from = isset($header->from) ? $header->from : '';
    $to = isset($header->to) ? $header->to : '';
    $date = isset($header->date) ? date('Y-m-d H:i:s', strtotime($header->date)) : '';
    $body = isset($header->body) ? htmlspecialchars(imap_utf8($header->body), ENT_QUOTES) : '';
    $emailId = $header->msgno; // use the message number as the email ID

    return (object) compact('subject', 'from', 'to', 'date', 'body', 'emailId');
    }, $pageMessages);

    // Close the connection to the Outlook IMAP server
    imap_close($inbox);

    // Render the outlook_imap view and pass in the $paginator and $pageMessages as variables
    return View::make('emails.outlook_imap', ['paginator' => $paginator, 'pageMessages' => $pageMessages]);

    }


    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        // Redirect to the index page if no search term was provided
        if (!$searchTerm) {
            return redirect()->route('emails.index');
        }

        // Call the index method with the search term
        return $this->index($request);
    }


        //

//     public function index(Request $request) {
//         // Outlook IMAP settings
//         $host = '{outlook.office365.com:993/imap/ssl}';
//         $username = 'est.nizar@hotmail.fr';
//         $password = 'psfqprrhxkvxtlwl';

//         // Connect to the Outlook IMAP server
//         $inbox = imap_open($host, $username, $password);

//         if (!$inbox) {
//             throw new Exception('Failed to connect to Outlook IMAP server: ' . imap_last_error());
//         }

//         // Get the number of messages in the inbox
//         $numMessages = imap_num_msg($inbox);

//         // Define the number of messages to display per page
//         $messagesPerPage = 10;

//         // Calculate the current page number
//         $currentPage = $request->input('page') ?? 1;

//         // Calculate the offset of the first message to display on the current page
//         $offset = ($currentPage - 1) * $messagesPerPage;

//         // Define the fields to fetch from the messages
//         $fields = 'SUBJECT FROM TO DATE';

//         // Search for all messages and sort them by date in descending order
// $searchTerm = $request->input('search');
// $searchQuery = $searchTerm ? 'TEXT "'.$searchTerm.'" ' : '';
// $emails = imap_search($inbox, $searchQuery.'ALL', SE_UID, 'SINCE "' . date('j F Y', strtotime("-30 days")) . '"');
// if (!is_array($emails)) {
//     $emails = array(); // initialize $emails as an empty array
// }
// rsort($emails);


//         // Fetch the headers and bodies for the selected page of messages
//         $pageEmails = array_slice($emails, $offset, $messagesPerPage);
//         $pageHeaders = imap_fetch_overview($inbox, implode(",", $pageEmails), FT_UID);
//         $pageMessages = array_map(function ($header) use ($inbox) {
//             $messageNumber = imap_msgno($inbox, $header->uid);
//             $body = imap_fetchbody($inbox, $messageNumber, '1');
//             $header->msgno = $messageNumber;
//             $header->body = $body;
//             return $header;
//         }, $pageHeaders);

//         // Close the connection to the Outlook IMAP server
//         imap_close($inbox);

//         // Create an array of message headers for the current page
// $pageMessages = array_map(function ($header) use ($fields) {
//     $subject = isset($header->subject) ? $header->subject : '';
//     $from = isset($header->from) ? $header->from : '';
//     $to = isset($header->to) ? $header->to : '';
//     $date = isset($header->date) ? date('Y-m-d H:i:s', strtotime($header->date)) : '';
//     $body = isset($header->body) ? htmlspecialchars(imap_utf8($header->body), ENT_QUOTES) : '';
//     $emailId = $header->msgno; // use the message number as the email ID


//     return (object) compact('subject', 'from', 'to', 'date', 'body');
// }, $pageMessages);


//         // Create a new LengthAwarePaginator instance
//     $paginator = new LengthAwarePaginator(
//     $pageMessages,
//     count($emails),
//     $messagesPerPage,
//     $currentPage,
//     ['path' => $request->url(), 'query' => array_merge($request->query(), ['search' => $searchTerm])]
// );
//         // Render the outlook_imap view and pass in the $paginator as a variable
//         return View::make('emails.outlook_imap', ['paginator' => $paginator]);
//     }


//     public function search(Request $request)
// {
//     // Outlook IMAP settings
//     $host = '{outlook.office365.com:993/imap/ssl}';
//     $username = 'est.nizar@hotmail.fr';
//     $password = 'psfqprrhxkvxtlwl';

//     // Connect to the Outlook IMAP server
//     $inbox = imap_open($host, $username, $password);

//     if (!$inbox) {
//         throw new Exception('Failed to connect to Outlook IMAP server: ' . imap_last_error());
//     }

//     // Get the search keyword from the request
//     $keyword = $request->input('q');

//     // Define the fields to fetch from the messages
//     $fields = 'SUBJECT FROM TO DATE';

//     // Search for messages that match the keyword
//     $emails = imap_search($inbox, 'OR SUBJECT "' . $keyword . '" BODY "' . $keyword . '"', SE_UID, 'SINCE "' . date('j F Y', strtotime("-30 days")) . '"');

//     // Create an array of message headers
//     $messages = array();
//     if (is_array($emails)) {
//         rsort($emails);

//         $messages = array_map(function ($email) use ($inbox, $fields) {
//             $header = false; // define the variable with a default value
//             if (is_array($header = imap_fetch_overview($inbox, $email, FT_UID)[0])) {
//                 $messageNumber = imap_msgno($inbox, $header->uid);
//                 $body = imap_fetchbody($inbox, $messageNumber, '1');
//                 $header->msgno = $messageNumber;
//                 $header->body = htmlspecialchars(imap_utf8($body), ENT_QUOTES);
//                 $header->date = date('Y-m-d H:i:s', strtotime($header->date));
//             }
//             return $header;
//         }, $emails);
//     }

//     // Close the connection to the Outlook IMAP server
//     imap_close($inbox);

//     // Create a new LengthAwarePaginator instance
//     $messagesPerPage = 10;
//     $currentPage = $request->input('page') ?? 1;
//     $offset = ($currentPage - 1) * $messagesPerPage;
//     $pageMessages = array_slice($messages, $offset, $messagesPerPage);
//     $paginator = new LengthAwarePaginator(
//         $pageMessages,
//         count($messages),
//         $messagesPerPage,
//         $currentPage,
//         ['path' => $request->url(), 'query' => $request->query()]
//     );

//     // Render the outlook_imap view and pass in the $paginator and $keyword variables as a variables
//     return View::make('emails.search', ['paginator' => $paginator, 'keyword' => $keyword]);
// }
}
