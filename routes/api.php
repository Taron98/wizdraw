<?php
if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
// Ignores notices and reports all other kinds... and warnings
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//    error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}
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

        Route::get('/version/{deviceType}/{versionId?}', [
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
        Route::get('/stores/{countryId}', [
            'as'   => 'country.stores',
            'uses' => 'CountryController@stores',
        ]);
        Route::get('/demo/active/{countryId}', [
            'as'   => 'country.listDemo',
            'uses' => 'CountryController@listDemo',
        ]);

        Route::get('demo/{id}/{receivingCurrency}/{senderCountryId}', [
            'as'   => 'country.showDemo',
            'uses' => 'CountryController@showDemo',
        ]);

    });

    Route::group(['prefix' => 'transfer/'], function () {
        Route::get('/suppliers/{countryId}/', [
            'as' => 'transfer.suppliers',
            'uses' => 'SupplierController@suppliers',
        ]);
        Route::get('/limit/{countryId}/', [
            'as'   => 'transfer.limit',
            'uses' => 'TransferController@limit',
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

            Route::post('/contact-fields/', [
                'as'   => 'client.contactfields',
                'uses' => 'ClientController@setContactFields',
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

            Route::get('/{id}/banks/', [
                'as'   => 'country.banks',
                'uses' => 'CountryController@banks',
            ]);

            Route::get('/bank/{bankId}', [
                'as'   => 'country.branches',
                'uses' => 'CountryController@branches',
            ]);

            Route::get('/use_qr_stores/{countryId}', [
                'as'   => 'country.use_qr_stores',
                'uses' => 'CountryController@use_qr_stores',
            ]);

            Route::get('/{id}/{receivingCurrency}', [
                'as'   => 'country.show',
                'uses' => 'CountryController@show',
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

            Route::post('/usedPaymentAgency/', [
                'as'   => 'transfer.usedPaymentAgency',
                'uses' => 'TransferController@alreadyUsedPaymentAgency',
            ]);

            Route::get('/provinces/{countryId}/', [
                'as'   => 'transfer.provinces',
                'uses' => 'TransferController@getProvinces',
            ]);

            Route::group(['prefix' => 'wizdrawCard/'], function () {
                Route::post('/sendSMS', [
                    'as' => 'transfer.wizdrawCard.sendSMS',
                    'uses' => 'TransferController@sendSMSWizdrawCard'
                ]);

                Route::post('/wizdrawCardCreateTransfer', [
                    'as' => 'transfer.wizdrawCard.sendSMS',
                    'uses' => 'TransferController@wizdrawCardCreateTransfer'
                ]);
            });

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
        Route::group(['prefix' => 'notifications/'], function (){
            Route::post('/token/', [
                'as'   => 'notifications.token',
                'uses' => 'NotificationsController@token',
            ]);
        });
        Route::group(['prefix' => '/images/'], function () {
            Route::get('/{type}/{transactionId}', [
                'as' => 'images.getImage',
                'uses' => 'ImagesController@getImage',
            ]);
        });
    });




});

