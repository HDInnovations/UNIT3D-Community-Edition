<?php

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
        //\App\Console\Commands\AutoPreWarning::class,
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

        // Manually Run Commands
        \App\Console\Commands\DemoSeed::class,
        \App\Console\Commands\GitUpdater::class,
        \App\Console\Commands\ClearCache::class,
        \App\Console\Commands\SetCache::class,
        \App\Console\Commands\TestMailSettings::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('AutoGroup')->daily();
        $schedule->command('AutoNerdStat')->hourly();
        $schedule->command('AutoGraveyard')->daily();
        $schedule->command('AutoHighspeedTag')->hourly();
        //$schedule->command('AutoPreWarning')->hourly();
        $schedule->command('AutoWarning')->hourly();
        $schedule->command('AutoDeactivateWarning')->hourly();
        $schedule->command('AutoRevokePermissions')->hourly();
        $schedule->command('AutoBan')->hourly();
        $schedule->command('AutoFlushPeers')->hourly();
        $schedule->command('AutoBonAllocation')->hourly();
        $schedule->command('AutoRemovePersonalFreeleech')->hourly();
        $schedule->command('AutoRemoveFeaturedTorrent')->hourly();
        $schedule->command('AutoRecycleInvites')->daily();
        $schedule->command('AutoRecycleActivityLog')->daily();
        $schedule->command('AutoRecycleFailedLogins')->daily();
        $schedule->command('AutoDisableInactiveUsers')->daily();
        $schedule->command('AutoSoftDeleteDisabledUsers')->daily();
        $schedule->command('AutoRecycleClaimedTorrentRequests')->daily();
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
