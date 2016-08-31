<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Api v1.0
Route::group(['prefix' => 'v1/'], function () {

    // Authentication
    Route::group(['prefix' => 'auth/'], function () {

        Route::post('login/', [
            'as'   => 'auth.login',
            'uses' => 'Auth\AuthController@login',
        ]);

        Route::post('signup/', [
            'as'   => 'auth.signup',
            'uses' => 'Auth\AuthController@signup',
        ]);

        Route::post('facebook/', [
            'as'   => 'auth.facebook',
            'uses' => 'Auth\AuthController@facebook',
        ]);

    });

});