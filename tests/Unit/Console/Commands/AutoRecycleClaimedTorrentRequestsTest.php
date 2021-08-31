<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoRecycleClaimedTorrentRequests
 */
it('runs successfully', function () {
    $this->artisan('auto:recycle_claimed_torrent_requests')
        ->expectsOutput('Automated Request Claim Reset Command Complete')
        ->assertExitCode(0)
        ->run();
});
