<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoFlushPeers
 */
class AutoFlushPeersTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:flush_peers')
            ->expectsOutput('Automated Flush Ghost Peers Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
