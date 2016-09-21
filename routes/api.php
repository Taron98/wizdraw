<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

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

    Route::group(['middleware' => 'auth'], function () {

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

            Route::post('verify/{verifyCode}/', [
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

        // Group
        Route::group(['prefix' => 'group/'], function () {

            Route::get('/{group}', [
                'as'   => 'group.show',
                'uses' => 'GroupController@show',
            ]);

            Route::post('/', [
                'as'   => 'group.create',
                'uses' => 'GroupController@create',
            ]);

            Route::post('/{id}', [
                'as'   => 'group.update',
                'uses' => 'GroupController@update',
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
