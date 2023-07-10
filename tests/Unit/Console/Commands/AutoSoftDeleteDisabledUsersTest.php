<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoSoftDeleteDisabledUsers
 */
class AutoSoftDeleteDisabledUsersTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:softdelete_disabled_users')
            ->expectsOutput('Automated Soft Delete Disabled Users Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
