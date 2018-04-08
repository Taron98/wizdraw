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

    Route::group(['prefix' => 'statuses/'], function (){
        Route::post('/notifyAborted/', [
            'as'   => 'statuses.notifyAborted',
            'uses' => 'StatusesController@notifyAborted',
        ]);
    });

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

        Route::get('/device/{deviceId}/version/{versionId}', [
            'as'   => 'user.version',
            'uses' => 'UserController@version',
        ]);

        Route::post('/reset/', [
            'as'   => 'user.reset',
            'uses' => 'UserController@reset',
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

            Route::post('/affiliate/{affiliateCode}', [
                'as'   => 'client.affiliate',
                'uses' => 'ClientController@affiliate',
            ]);

            Route::post('/updatevip/', [
                'as'   => 'client.vip',
                'uses' => 'ClientController@updateVips',
            ]);

            Route::post('/changename/', [
                'as'   => 'client.changename',
                'uses' => 'ClientController@changeName',
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

            Route::get('/active/', [
                'as'   => 'country.active',
                'uses' => 'CountryController@list',
            ]);

            Route::get('/{id}/', [
                'as'   => 'country.show',
                'uses' => 'CountryController@show',
            ]);

            Route::get('/{id}/banks/', [
                'as'   => 'country.banks',
                'uses' => 'CountryController@banks',
            ]);

            Route::get('/bank/{bankId}', [
                'as'   => 'country.branches',
                'uses' => 'CountryController@branches',
            ]);

            Route::get('/stores/{countryId}', [
                'as'   => 'country.stores',
                'uses' => 'CountryController@stores',
            ]);

            Route::get('/use_qr_stores/{countryId}', [
                'as'   => 'country.use_qr_stores',
                'uses' => 'CountryController@use_qr_stores',
            ]);

        });

        // Transfer
        Route::group(['prefix' => 'transfer/'], function () {

            Route::get('/status', [
                'as'   => 'transfer.statuses',
                'uses' => 'TransferController@statuses',
            ]);

            Route::get('/last', [
                'as'   => 'transfer.last',
                'uses' => 'TransferController@last',
            ]);

            Route::get('/able/', [
                'as'   => 'transfer.able',
                'uses' => 'TransferController@able',
            ]);

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

            Route::post('/{transfer}/status/', [
                'as'   => 'transfer.status',
                'uses' => 'TransferController@status',
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

            Route::get('/limit/{countryId}/', [
                'as'   => 'transfer.limit',
                'uses' => 'TransferController@limit',
            ]);

            Route::post('/usedPaymentAgency/', [
                'as'   => 'transfer.usedPaymentAgency',
                'uses' => 'TransferController@alreadyUsedPaymentAgency',
            ]);

        });

        // Feedback
        Route::group(['prefix' => 'feedback/'], function () {

            Route::get('/questions/', [
                'as'   => 'feedback.questions',
                'uses' => 'FeedbackController@questions',
            ]);

            Route::post('/', [
                'as'   => 'feedback.create',
                'uses' => 'FeedbackController@create',
            ]);

        });


    });


});
