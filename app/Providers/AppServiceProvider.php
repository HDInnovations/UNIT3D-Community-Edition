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

use App\Helpers\HiddenCaptcha;
use App\Interfaces\WishInterface;
use App\Models\Page;
use App\Models\Torrent;
use App\Models\User;
use App\Observers\TorrentObserver;
use App\Observers\UserObserver;
use App\Repositories\WishRepository;
use App\Services\Clients\OmdbClient;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        // OMDB
        $this->app->bind(OmdbClient::class, function ($app) {
            $key = config('api-keys.omdb');

            return new OmdbClient($key);
        });

        // Wish
        $this->app->bind(WishInterface::class, WishRepository::class);

        // Hidden Captcha
        $this->app->bind('hiddencaptcha', 'App\Helpers\HiddenCaptcha');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // User Observer For Cache
        User::observe(UserObserver::class);

        // Torrent Observer For Cache
        Torrent::observe(TorrentObserver::class);

        // Custom validation for the email whitelist/blacklist
        validator()->extend('email_list', 'App\Validators\EmailValidator@validateEmailList');

        // Share $footer_pages across all views
        view()->composer('*', function (View $view) {
            $footer_pages = cache()->remember('cached-pages', 3600, function () {
                return Page::select(['id', 'name', 'slug', 'created_at'])->take(6)->get();
            });

            $view->with(compact('footer_pages'));
        });

        // Hidden Captcha
        Blade::directive('hiddencaptcha', function ($mustBeEmptyField = '_username') {
            return "<?= App\Helpers\HiddenCaptcha::render($mustBeEmptyField); ?>";
        });

        $this->app['validator']->extendImplicit(
            'hiddencaptcha',
            function ($attribute, $value, $parameters, $validator) {
                $minLimit = (isset($parameters[0]) && is_numeric($parameters[0])) ? $parameters[0] : 0;
                $maxLimit = (isset($parameters[1]) && is_numeric($parameters[1])) ? $parameters[1] : 1200;
                if (!HiddenCaptcha::check($validator, $minLimit, $maxLimit)) {
                    $validator->setCustomMessages(['hiddencaptcha' => 'Captcha error']);

                    return false;
                }

                return true;
            }
        );
    }
}
