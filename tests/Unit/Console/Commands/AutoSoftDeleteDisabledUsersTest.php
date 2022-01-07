<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoSoftDeleteDisabledUsers
 */
class AutoSoftDeleteDisabledUsersTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:softdelete_disabled_users')
            ->expectsOutput('Automated Soft Delete Disabled Users Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
