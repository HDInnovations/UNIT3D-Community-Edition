<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Providers;

use App\Repositories\WishInterface;
use App\Repositories\WishRepository;
use App\Services\Clients\OmdbClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

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
        // we can now inject this class and it will auto resolve for us
        $this->app->bind(OmdbClient::class, function ($app) {
            $key = env('OMDB_API_KEY', config('api-keys.omdb'));
            return new OmdbClient($key);
        });

        // registering a interface to a concrete class, so we can inject the interface
        $this->app->bind(WishInterface::class, WishRepository::class);
    }
}
