<?php

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckForAdmin;
use App\Http\Middleware\CheckForMaintenanceMode;
use App\Http\Middleware\CheckForModo;
use App\Http\Middleware\CheckForOwner;
use App\Http\Middleware\CheckIfAlreadyVoted;
use App\Http\Middleware\CheckIfBanned;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\Http2ServerPush;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SetLanguage;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\TwoStepAuth;
use App\Http\Middleware\UpdateLastAction;
use App\Http\Middleware\VerifyCsrfToken;
use Bepsvpt\SecureHeaders\SecureHeadersMiddleware;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class Kernel extends HttpKernel
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
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,

        // Extra
        SecureHeadersMiddleware::class,
        Http2ServerPush::class,
        //\App\Http\Middleware\HtmlEncrypt::class,
        //\App\Http\Middleware\ProAjaxMiddleware::class,
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
        'admin'         => CheckForAdmin::class,
        'auth'          => Authenticate::class,
        'auth.basic'    => AuthenticateWithBasicAuth::class,
        'banned'        => CheckIfBanned::class,
        'bindings'      => SubstituteBindings::class,
        'cache.headers' => SetCacheHeaders::class,
        'can'           => Authorize::class,
        'check_ip'      => CheckIfAlreadyVoted::class,
        'csrf'          => VerifyCsrfToken::class,
        'guest'         => RedirectIfAuthenticated::class,
        'language'      => SetLanguage::class,
        'modo'          => CheckForModo::class,
        'owner'         => CheckForOwner::class,
        'throttle'      => ThrottleRequests::class,
        'twostep'       => TwoStepAuth::class,
        'signed'        => ValidateSignature::class,
        'verified'      => EnsureEmailIsVerified::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        StartSession::class,
        ShareErrorsFromSession::class,
        Authenticate::class,
        ThrottleRequests::class,
        AuthenticateSession::class,
        SubstituteBindings::class,
        Authorize::class,
    ];
}
