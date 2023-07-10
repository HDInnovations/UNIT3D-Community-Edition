<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoDisableInactiveUsers
 */
class AutoDisableInactiveUsersTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:disable_inactive_users')
            ->expectsOutput('Automated User Disable Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
