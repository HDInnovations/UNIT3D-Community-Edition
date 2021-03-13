<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckIfBanned;
use App\Http\Middleware\CheckPrivilege;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SetLanguage;
use App\Http\Middleware\TwoStepAuth;
use App\Http\Middleware\UpdateLastAction;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
        // Default Laravel
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        //\App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,

        // Extra
        \Bepsvpt\SecureHeaders\SecureHeadersMiddleware::class,
        \App\Http\Middleware\Http2ServerPush::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            SubstituteBindings::class,
            VerifyCsrfToken::class,
            UpdateLastAction::class,
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
        'auth'          => Authenticate::class,
        'auth.basic'    => AuthenticateWithBasicAuth::class,
        'banned'        => CheckIfBanned::class,
        'bindings'      => SubstituteBindings::class,
        'cache.headers' => SetCacheHeaders::class,
        'can'           => Authorize::class,
        'csrf'          => VerifyCsrfToken::class,
        'guest'         => RedirectIfAuthenticated::class,
        'language'      => SetLanguage::class,
        'throttle'      => ThrottleRequests::class,
        'twostep'       => TwoStepAuth::class,
        'signed'        => ValidateSignature::class,
        'verified'      => EnsureEmailIsVerified::class,
        'role'          => CheckRole::class,
        'privilege'     => CheckPrivilege::class,
    ];
}
