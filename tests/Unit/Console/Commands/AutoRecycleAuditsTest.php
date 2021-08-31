<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoRecycleAudits
 */
it('runs successfully', function () {
    $this->artisan('auto:recycle_activity_log')
        ->expectsOutput('Automated Purge Old Audits Command Complete')
        ->assertExitCode(0)
        ->run();
});
