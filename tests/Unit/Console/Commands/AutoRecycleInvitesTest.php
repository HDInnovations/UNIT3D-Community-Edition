<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRecycleInvites
 */
class AutoRecycleInvitesTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:recycle_invites')
            ->expectsOutput('Automated Purge Unaccepted Invites Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
