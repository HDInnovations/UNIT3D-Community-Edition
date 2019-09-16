<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Logout' => [
            \App\Listeners\LogoutListener::class,
        ],
        'Illuminate\Auth\Events\Login' => [
            \App\Listeners\LoginListener::class,
        ],
        'Illuminate\Auth\Events\Failed' => [
            \App\Listeners\FailedLoginListener::class,
        ],
        'Gstt\Achievements\Event\Unlocked' => [
            \App\Listeners\AchievementUnlocked::class,
        ],
        'Spatie\Backup\Events\BackupZipWasCreated' => [
            \App\Listeners\PasswordProtectBackup::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
