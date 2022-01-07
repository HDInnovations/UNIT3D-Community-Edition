<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoDisableInactiveUsers
 */
class AutoDisableInactiveUsersTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:disable_inactive_users')
            ->expectsOutput('Automated User Disable Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
