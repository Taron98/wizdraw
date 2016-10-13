<?php

namespace Wizdraw\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Wizdraw\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
//            \Wizdraw\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
            'cors',
            'camel.casing',
        ],

        'auth' => [
            'jwt.auth',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'        => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic'  => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'    => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can'         => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'       => \Wizdraw\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'    => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'jwt.auth'    => \Tymon\JWTAuth\Middleware\GetUserFromToken::class,
        'jwt.refresh' => \Tymon\JWTAuth\Middleware\RefreshToken::class,
        'cors'        => \Barryvdh\Cors\HandleCors::class,
        'camel.casing'   => \Wizdraw\Http\Middleware\CamelCasing::class,
    ];
}
