<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoSoftDeleteDisabledUsers
 */
it('runs successfully', function () {
    $this->artisan('auto:softdelete_disabled_users')
        ->expectsOutput('Automated Soft Delete Disabled Users Command Complete')
        ->assertExitCode(0)
        ->run();
});
