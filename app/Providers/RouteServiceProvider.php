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

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    final public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function (): void {
            Route::prefix('api')
                ->middleware(['chat'])
                ->group(base_path('routes/vue.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::prefix('announce')
                ->middleware('announce')
                ->group(base_path('routes/announce.php'));

            Route::middleware('rss')
                ->group(base_path('routes/rss.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('web', fn (Request $request): Limit => $request->user()
            ? Limit::perMinute(30)->by($request->user()->id)
            : Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(30)->by($request->ip()));
        RateLimiter::for('announce', fn (Request $request) => Limit::perMinute(500)->by($request->ip()));
        RateLimiter::for('chat', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?? $request->ip()));
        RateLimiter::for('rss', fn (Request $request) => Limit::perMinute(30)->by($request->ip()));
    }
}
