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

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Scheduled Commands
        \App\Console\Commands\AutoNerdStat::class,
        \App\Console\Commands\AutoBonAllocation::class,
        \App\Console\Commands\AutoHighspeedTag::class,
        \App\Console\Commands\AutoPreWarning::class,
        \App\Console\Commands\AutoWarning::class,
        \App\Console\Commands\AutoDeactivateWarning::class,
        \App\Console\Commands\AutoRevokePermissions::class,
        \App\Console\Commands\AutoBan::class,
        \App\Console\Commands\AutoFlushPeers::class,
        \App\Console\Commands\AutoGroup::class,
        \App\Console\Commands\AutoRemovePersonalFreeleech::class,
        \App\Console\Commands\AutoRemoveFeaturedTorrent::class,
        \App\Console\Commands\AutoGraveyard::class,
        \App\Console\Commands\IrcBroadcast::class,
        \App\Console\Commands\IrcMessage::class,
        \App\Console\Commands\AutoRecycleInvites::class,
        \App\Console\Commands\AutoRecycleActivityLog::class,
        \App\Console\Commands\AutoRecycleFailedLogins::class,
        \App\Console\Commands\AutoDisableInactiveUsers::class,
        \App\Console\Commands\AutoSoftDeleteDisabledUsers::class,
        \App\Console\Commands\AutoRecycleClaimedTorrentRequests::class,
        \App\Console\Commands\AutoCorrectHistory::class,

        // Manually Run Commands
        \App\Console\Commands\DemoSeed::class,
        \App\Console\Commands\GitUpdater::class,
        \App\Console\Commands\ClearCache::class,
        \App\Console\Commands\SetCache::class,
        \App\Console\Commands\TestMailSettings::class,
        \App\Console\Commands\FetchGenres::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('auto:group ')->daily();
        $schedule->command('auto:nerdstat ')->hourly();
        $schedule->command('auto:graveyard')->daily();
        $schedule->command('auto:highspeed_tag')->hourly();
        $schedule->command('auto:prewarning')->hourly();
        $schedule->command('auto:warning')->daily();
        $schedule->command('auto:deactivate_warning')->hourly();
        $schedule->command('auto:revoke_permissions')->hourly();
        $schedule->command('auto:ban')->hourly();
        $schedule->command('auto:flush_peers')->hourly();
        //$schedule->command('auto:correct_history')->hourly();
        $schedule->command('auto:bon_allocation')->hourly();
        $schedule->command('auto:remove_personal_freeleech')->hourly();
        $schedule->command('auto:remove_featured_torrent')->hourly();
        $schedule->command('auto:recycle_invites')->daily();
        $schedule->command('auto:recycle_activity_log')->daily();
        $schedule->command('auto:recycle_failed_logins')->daily();
        $schedule->command('auto:disable_inactive_users')->daily();
        $schedule->command('auto:softdelete_disabled_users')->daily();
        $schedule->command('auto:recycle_claimed_torrent_requests')->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
