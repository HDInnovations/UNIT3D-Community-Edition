<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoSoftDeleteDisabledUsers
 */
class AutoSoftDeleteDisabledUsersTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully()
    {
        $this->artisan('auto:softdelete_disabled_users')
            ->expectsOutput('Automated Soft Delete Disabled Users Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
