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

use App\Helpers\ByteUnits;
use App\Helpers\HiddenCaptcha;
use App\Interfaces\ByteUnitsInterface;
use App\Models\Page;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
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
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     */
    public function register(): void
    {
        // Hidden Captcha
        $this->app->bind('hiddencaptcha', HiddenCaptcha::class);

        // Gabrielelana byte-units
        $this->app->bind(ByteUnitsInterface::class, ByteUnits::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // User Observer For Cache
        User::observe(UserObserver::class);

        // Share $footer_pages across all views
        view()->composer('*', function (View $view): void {
            $footerPages = cache()->remember('cached-pages', 3_600, fn () => Page::select(['id', 'name', 'created_at'])->take(6)->get());

            $view->with(['footer_pages' => $footerPages]);
        });

        // Hidden Captcha
        Blade::directive('hiddencaptcha', fn ($mustBeEmptyField = '_username') => sprintf('<?= App\Helpers\HiddenCaptcha::render(%s); ?>', $mustBeEmptyField));

        $this->app['validator']->extendImplicit(
            'hiddencaptcha',
            function ($attribute, $value, $parameters, $validator) {
                $minLimit = (isset($parameters[0]) && is_numeric($parameters[0])) ? $parameters[0] : 0;
                $maxLimit = (isset($parameters[1]) && is_numeric($parameters[1])) ? $parameters[1] : 1_200;

                if (!HiddenCaptcha::check($validator, $minLimit, $maxLimit)) {
                    $validator->setCustomMessages(['hiddencaptcha' => 'Captcha error']);

                    return false;
                }

                return true;
            }
        );

        // Add attributes to vite scripts and styles
        Vite::useScriptTagAttributes([
            'crossorigin' => 'anonymous',
        ]);

        Vite::useStyleTagAttributes([
            'crossorigin' => 'anonymous',
        ]);

        $this->bootAuth();
        $this->bootRoute();
    }

    public function bootAuth(): void
    {
        Auth::provider('cache-user', fn () => resolve(CacheUserProvider::class));
    }

    public function bootRoute(): void
    {
        RateLimiter::for('web', fn (Request $request): Limit => $request->user()
            ? Limit::perMinute(30)->by('web'.$request->user()->id)
            : Limit::perMinute(5)->by('web'.$request->ip()));
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(30)->by('api'.$request->ip()));
        RateLimiter::for('announce', fn (Request $request) => Limit::perMinute(500)->by('announce'.$request->ip()));
        RateLimiter::for('chat', fn (Request $request) => Limit::perMinute(60)->by('chat'.($request->user()?->id ?? $request->ip())));
        RateLimiter::for('rss', fn (Request $request) => Limit::perMinute(30)->by('rss'.$request->ip()));
    }
}
