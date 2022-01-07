<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoFlushPeers
 */
class AutoFlushPeersTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:flush_peers')
            ->expectsOutput('Automated Flush Ghost Peers Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
