<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoFlushPeers
 */
class AutoFlushPeersTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:flush_peers')
            ->expectsOutput('Automated Flush Ghost Peers Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
