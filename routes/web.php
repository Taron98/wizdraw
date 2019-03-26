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

use Wizdraw\Notifications\TransferAborted;

Route::get('/', function () {
    $user = new \Wizdraw\Models\User();
    $transfer = new \Wizdraw\Models\Transfer();
    $user->notify((new TransferAborted($transfer)));
    return view('welcome');
});
