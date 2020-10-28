<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRecycleAudits
 */
class AutoRecycleAuditsTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully()
    {
        $this->artisan('auto:recycle_activity_log')
            ->expectsOutput('Automated Purge Old Audits Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
