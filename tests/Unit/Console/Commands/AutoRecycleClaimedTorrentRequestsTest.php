<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRecycleClaimedTorrentRequests
 */
class AutoRecycleClaimedTorrentRequestsTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:recycle_claimed_torrent_requests')
            ->expectsOutput('Automated Request Claim Reset Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
