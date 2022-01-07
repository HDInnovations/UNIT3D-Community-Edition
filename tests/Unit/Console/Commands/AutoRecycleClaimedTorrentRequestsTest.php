<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRecycleClaimedTorrentRequests
 */
class AutoRecycleClaimedTorrentRequestsTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:recycle_claimed_torrent_requests')
            ->expectsOutput('Automated Request Claim Reset Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
