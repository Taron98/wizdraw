<?php

namespace Wizdraw\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Wizdraw\Models\Group;
use Wizdraw\Models\Transfer;
use Wizdraw\Policies\GroupPolicy;
use Wizdraw\Policies\TransferPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Group::class    => GroupPolicy::class,
        Transfer::class => TransferPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
