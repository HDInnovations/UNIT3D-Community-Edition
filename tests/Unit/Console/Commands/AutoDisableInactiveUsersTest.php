<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoDisableInactiveUsers
 */
it('runs successfully', function () {
    $this->artisan('auto:disable_inactive_users')
        ->expectsOutput('Automated User Disable Command Complete')
        ->assertExitCode(0)
        ->run();
});
