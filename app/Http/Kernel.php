<?php

namespace App\Http;

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
        //Default Laravel
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,

        //Secure Headers
        \Bepsvpt\SecureHeaders\SecureHeadersMiddleware::class,

        //HTTP2ServerPush
        \App\Http\Middleware\Http2ServerPush::class,

        //HtmlEncrypt
        //\App\Http\Middleware\HtmlEncrypt::class,

        //AJAX
        //\App\Http\Middleware\ProAjaxMiddleware::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
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
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
        'admin' => \App\Http\Middleware\CheckForAdmin::class,
        'private' => \App\Http\Middleware\CheckForPrivate::class,
        'modo' => \App\Http\Middleware\CheckForModo::class,
        'check_ip' => \App\Http\Middleware\CheckIfAlreadyVoted::class,
        'language' => \App\Http\Middleware\SetLanguage::class,
        'censor' => \App\Http\Middleware\LanguageCensor::class,
        'banned' => \App\Http\Middleware\CheckIfBanned::class,
        'active' => \App\Http\Middleware\CheckIfActive::class,
        'online' => \App\Http\Middleware\CheckIfOnline::class,
        'twostep' => \App\Http\Middleware\TwoStepAuth::class,
    ];
}
