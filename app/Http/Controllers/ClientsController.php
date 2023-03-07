<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
class ClientsController extends Controller
{
    public function index() {

        return view('backend.layouts.clients.clients');
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

    public function sendEmail() {

    }
}
