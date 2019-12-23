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

namespace App\Providers;

use Illuminate\Contracts\Broadcasting\Factory;
use Illuminate\Support\ServiceProvider;

final class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Contracts\Broadcasting\Factory
     */
    private $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
        parent::__construct();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->factory->routes();

        require base_path('routes/channels.php');
    }
}
