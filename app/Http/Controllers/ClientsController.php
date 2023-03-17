<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use Excel;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;
use App\Models\Email;

class ClientsController extends Controller
{
    public function search(Request $request) {
        $search = $request->input('search');
    //if a search query is found it return data by it if not it will return it normal
        $clients = Clients::when($search, function ($query) use ($search) {
                return $query->where('email', 'LIKE', '%' . $search . '%');
            })
            ->paginate(8);
    return view('backend.layouts.clients.clients', compact('clients', 'search'));
    }

    public function addClient() {
        return view('backend.layouts.clients.add-client');
    }
    public function saveClient(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',

        ]);

        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;


        $client = new Clients();
        $client->name = $name;
        $client->email = $email;
        $client->phone = $phone;

        $client->save();

        return redirect('home');
    }

    public function editClient($id){
        $data = Clients::where('id', '=', $id)->first();
        return view('backend.layouts.clients.edit-client', compact('data'));
    }

    public function updateClient(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        $id = $request->id;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;

        Clients::where('id', '=', $id)->update([
            'name'=>$name,
            'email'=>$email,
            'phone'=>$phone,
        ]);
        return redirect('home');
    }

    public function deleteClient($id){
        Clients::where('id', '=', $id)->delete();
        return redirect()->back()->with('success', 'client deleted successfully');
    }

    public function excelExport() {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }

    public function excelImport(Request $request) {
        Excel::import(new ClientsImport, $request->file('file'));
        return redirect('/clients');
    }
}
