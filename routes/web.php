<?php

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\EmailController;
use App\Mail\ContactClients;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Auth::routes(['verify'=> true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified');

Route::controller(ClientsController::class)->group(function() {
    Route::get('/home/add-client', 'addClient')->name('addClients')->middleware('auth');
    Route::post('/save-client', 'saveClient')->middleware('auth');
    Route::get('/edit-client/{id}', 'editClient')->middleware('auth');
    Route::post('/update-client', 'updateClient')->middleware('auth');
    Route::get('/delete-client/{id}', 'deleteClient')->middleware('auth');
    Route::get('/clients', 'search')->name('clients.search')->middleware('auth');
});
Route::get('/contact', function () {
    return view('backend.layouts.Emails.compose');
});
// this route is responsible for sending emails to change configuration go to the env file and mail folder inside app folder
Route::post('/contact', function () {
    $data = request(['email', 'subject', 'message']);
    Mail::to($data['email'])->send(new ContactClients($data));
    return redirect('/microsoft')->with('flash', 'Email sent successfully')->middleware('auth');
});
Route::get('/contact', [EmailController::class,'selectEmail'])->middleware('auth');


//test mail
Route::get('/microsoft', [EmailController::class, 'showEmails'])->middleware('verified');
Route::get('/fetch-latest-email', [EmailController::class, 'fetchEmails'])->name('fetch.latest.email')->middleware('verified');
Route::get('/emails', [EmailController::class, 'showEmails'])->name('emails.index');

//export clients from excel file
Route::get('/excel', function () {
    return view('backend.layouts.clients.excel.excel');
})->name('excel')->middleware('verified');
Route::get('/export-client', [ClientsController::class, 'excelExport'])->name('export-client')->middleware('verified');
Route::post('/import-client', [ClientsController::class, 'excelImport'])->name('import-client')->middleware('verified');




