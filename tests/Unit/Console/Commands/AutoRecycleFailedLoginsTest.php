<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoRecycleFailedLogins
 */
it('runs successfully', function () {
    $this->artisan('auto:recycle_failed_logins')
        ->expectsOutput('Automated Purge Old Failed Logins Command Complete')
        ->assertExitCode(0)
        ->run();
});
