<?php

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \Assada\Achievements\AchievementsServiceProvider::class,
        \Spatie\CookieConsent\CookieConsentServiceProvider::class,
        \Intervention\Image\ImageServiceProvider::class,
        \App\Providers\FortifyServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->throttleWithRedis();

        $middleware->append(\App\Http\Middleware\BlockIpAddress::class);

        $middleware->web([
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \App\Http\Middleware\UpdateLastAction::class,
            \HDVinnie\SecureHeaders\SecureHeadersMiddleware::class,
            'throttle:web',
        ]);

        $middleware->api('throttle:api');

        $middleware->group('chat', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\UpdateLastAction::class,
            \HDVinnie\SecureHeaders\SecureHeadersMiddleware::class,
            'throttle:chat',
        ]);

        $middleware->group('announce', [
            'throttle:announce',
        ]);

        $middleware->group('rss', [
            'throttle:rss',
        ]);

        $middleware->replace(\Illuminate\Foundation\Http\Middleware\TrimStrings::class, \App\Http\Middleware\TrimStrings::class);
        $middleware->replace(\Illuminate\Http\Middleware\TrustProxies::class, \App\Http\Middleware\TrustProxies::class);

        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckForAdmin::class,
            'banned' => \App\Http\Middleware\CheckIfBanned::class,
            'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'language' => \App\Http\Middleware\SetLanguage::class,
            'modo' => \App\Http\Middleware\CheckForModo::class,
            'owner' => \App\Http\Middleware\CheckForOwner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReport([
            \Illuminate\Queue\MaxAttemptsExceededException::class,
        ]);

        $exceptions->reportable(function (Throwable $e): void {
        });
    })->create();
