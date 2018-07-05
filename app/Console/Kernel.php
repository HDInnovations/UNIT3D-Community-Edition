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
        \App\Console\Commands\autoNerdStat::class,
        \App\Console\Commands\bonAllocation::class,
        \App\Console\Commands\autoSeedbox::class,
        //\App\Console\Commands\autoPreWarning::class,
        \App\Console\Commands\autoWarning::class,
        \App\Console\Commands\deactivateWarning::class,
        \App\Console\Commands\revokePermissions::class,
        \App\Console\Commands\autoBan::class,
        \App\Console\Commands\FlushPeers::class,
        \App\Console\Commands\autoGroup::class,
        \App\Console\Commands\removePersonalFreeleech::class,
        \App\Console\Commands\removeFeaturedTorrent::class,
        \App\Console\Commands\autoGraveyard::class,
        \App\Console\Commands\ircBroadcast::class,
        \App\Console\Commands\ircMessage::class,
        \App\Console\Commands\recycleInvites::class,
        \App\Console\Commands\recycleActivityLog::class,
        \App\Console\Commands\recycleFailedLogins::class,
        \App\Console\Commands\demoSeed::class,
        \App\Console\Commands\gitUpdate::class,
        \App\Console\Commands\clearCache::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('autoNerdStat')->hourly();
        $schedule->command('bonAllocation')->hourly();
        $schedule->command('autoSeedbox')->hourly();
        //$schedule->command('autoPreWarning')->hourly();
        $schedule->command('autoWarning')->hourly();
        $schedule->command('deactivateWarning')->hourly();
        $schedule->command('revokePermissions')->hourly();
        $schedule->command('autoBan')->hourly();
        $schedule->command('FlushPeers')->hourly();
        $schedule->command('autoGroup')->daily();
        $schedule->command('removePersonalFreeleech')->hourly();
        $schedule->command('removeFeaturedTorrent')->hourly();
        $schedule->command('autoGraveyard')->daily();
        $schedule->command('recycleInvites')->daily();
        $schedule->command('recycleActivityLog')->daily();
        $schedule->command('recycleFailedLogins')->daily();
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
