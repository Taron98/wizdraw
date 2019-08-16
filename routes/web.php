<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    \Illuminate\Support\Facades\Mail::send('emails.verification', [], function ($m) {
        $m->from('bns258456@gmail.com', 'Verify your account');
        $m->to('mailfortest159357@gmail.com')->subject('Your Reminder!');
    });
//    return view('welcome');
});
