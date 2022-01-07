<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\SyncPeers
 */
class SyncPeersTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:sync_peers')
            ->expectsOutput('Torrent Peer Syncing Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
