<?php

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
        'Gstt\Achievements\Event\Unlocked' => [
        'App\Listeners\AchievementUnlocked',
    ],
        'Illuminate\Auth\Events\Failed' => [
        'App\Listeners\RecordFailedLoginAttempt',
    ],
        'Illuminate\Auth\Events\Login' => [
        'App\Listeners\UpdateLastLogin',
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
