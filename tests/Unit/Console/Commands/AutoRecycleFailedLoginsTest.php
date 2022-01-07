<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRecycleFailedLogins
 */
class AutoRecycleFailedLoginsTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:recycle_failed_logins')
            ->expectsOutput('Automated Purge Old Failed Logins Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
