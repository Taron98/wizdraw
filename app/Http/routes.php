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

    Route::group(['middleware' => 'api'], function () {

        // User
        Route::group(['prefix' => 'user/'], function () {

            Route::post('password/', [
                'as'   => 'user.password',
                'uses' => 'UserController@password',
            ]);

            Route::post('code/', [
                'as'   => 'user.code',
                'uses' => 'UserController@code',
            ]);

            Route::post('verify/{verifyCode}', [
                'as'   => 'user.verify',
                'uses' => 'UserController@verify',
            ]);

            Route::get('device/{deviceId}/', [
                'as'   => 'user.device',
                'uses' => 'UserController@device',
            ]);

        });

        // Client
        Route::group(['prefix' => 'client/'], function () {

            Route::post('/', [
                'as'   => 'client.update',
                'uses' => 'ClientController@update',
            ]);

            Route::post('phone/', [
                'as'   => 'client.phone',
                'uses' => 'ClientController@phone',
            ]);

        });

        // Identity Type
        Route::group(['prefix' => 'identitytype/'], function () {

            Route::get('/', [
                'as'   => 'identitytype.all',
                'uses' => 'IdentityTypeController@all',
            ]);

        });

    });

});