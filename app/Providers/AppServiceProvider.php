<?php

namespace Wizdraw\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Wizdraw\Services\ValidatorService;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootValidator();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ClientInterface::class,Client::class);
    }

    private function bootValidator()
    {
        Validator::resolver(function ($translator, $data, $rules, $messages = [], $customAttributes = []) {
            return new ValidatorService($translator, $data, $rules, $messages, $customAttributes);
        });
    }

}
