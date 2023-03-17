<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
            $emails = Email::paginate('8');
        return view('backend.layouts.app',compact('emails'));
    }
}
