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

        Route::post('/login/', [
            'as'   => 'auth.login',
            'uses' => 'Auth\AuthController@login',
        ]);

        Route::post('/signup/', [
            'as'   => 'auth.signup',
            'uses' => 'Auth\AuthController@signup',
        ]);

        Route::post('/facebook/', [
            'as'   => 'auth.facebook',
            'uses' => 'Auth\AuthController@facebook',
        ]);

        Route::post('/token/', [
            'as'   => 'auth.token',
            'uses' => 'Auth\AuthController@token',
        ]);

    });

    // User
    Route::group(['prefix' => 'user/'], function () {

        Route::get('/device/{deviceId}/', [
            'as'   => 'user.device',
            'uses' => 'UserController@device',
        ]);

    });

    // Country
    Route::group(['prefix' => 'country/'], function () {

        Route::get('/', [
            'as'   => 'country.list',
            'uses' => 'CountryController@list',
        ]);

        Route::post('/location/', [
            'as'   => 'country.showByLocation',
            'uses' => 'CountryController@showByLocation',
        ]);

    });

    Route::group(['middleware' => 'auth'], function () {

        // User
        Route::group(['prefix' => 'user/'], function () {

            Route::post('/', [
                'as'   => 'user.update',
                'uses' => 'UserController@update',
            ]);

            Route::post('/password/', [
                'as'   => 'user.password',
                'uses' => 'UserController@password',
            ]);

            Route::post('/code/', [
                'as'   => 'user.code',
                'uses' => 'UserController@code',
            ]);

            Route::post('/verify/{verifyCode}/', [
                'as'   => 'user.verify',
                'uses' => 'UserController@verify',
            ]);

        });

        // Client
        Route::group(['prefix' => 'client/'], function () {

            Route::post('/', [
                'as'   => 'client.update',
                'uses' => 'ClientController@update',
            ]);

            Route::post('/phone/', [
                'as'   => 'client.phone',
                'uses' => 'ClientController@phone',
            ]);

        });

        // Group
        Route::group(['prefix' => 'group/'], function () {

            Route::get('/{group}/', [
                'as'   => 'group.show',
                'uses' => 'GroupController@show',
            ]);

            Route::get('/', [
                'as'   => 'group.list',
                'uses' => 'GroupController@list',
            ]);

            Route::post('/', [
                'as'   => 'group.create',
                'uses' => 'GroupController@create',
            ]);

            Route::post('/{group}/', [
                'as'   => 'group.update',
                'uses' => 'GroupController@update',
            ]);

            Route::delete('/{group}/client', [
                'as'   => 'group.removeClient',
                'uses' => 'GroupController@removeClient',
            ]);

            Route::post('/{group}/client', [
                'as'   => 'group.addClient',
                'uses' => 'GroupController@addClient',
            ]);

        });

        // Identity Type
        Route::group(['prefix' => 'identitytype/'], function () {

            Route::get('/', [
                'as'   => 'identitytype.all',
                'uses' => 'IdentityTypeController@all',
            ]);

        });

        // Country
        Route::group(['prefix' => 'country/'], function () {

            Route::get('/{id}/', [
                'as'   => 'country.show',
                'uses' => 'CountryController@show',
            ]);

            Route::get('/{id}/banks/', [
                'as'   => 'country.banks',
                'uses' => 'CountryController@banks',
            ]);

        });

        // Transfer
        Route::group(['prefix' => 'transfer/'], function () {

            Route::get('/{transfer}/', [
                'as'   => 'transfer.show',
                'uses' => 'TransferController@show',
            ]);

            Route::post('/', [
                'as'   => 'transfer.create',
                'uses' => 'TransferController@create',
            ]);

            Route::post('/{transfer}/receipt/', [
                'as'   => 'transfer.addReceipt',
                'uses' => 'TransferController@addReceipt',
            ]);

            Route::get('/', [
                'as'   => 'transfer.list',
                'uses' => 'TransferController@list',
            ]);

            Route::post('/nearby/', [
                'as'   => 'transfer.nearby',
                'uses' => 'TransferController@nearby',
            ]);

            Route::post('/{transfer}/feedback/', [
                'as'   => 'transfer.feedback',
                'uses' => 'TransferController@feedback',
            ]);

        });

        // Feedback
        Route::group(['prefix' => 'feedback/'], function () {

            Route::get('/questions/', [
                'as'   => 'feedback.questions',
                'uses' => 'FeedbackController@questions',
            ]);

        });

    });

});
