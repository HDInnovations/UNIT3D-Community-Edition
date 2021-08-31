<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\SyncPeers
 */
it('runs successfully', function () {
    $this->artisan('auto:sync_peers')
        ->expectsOutput('Torrent Peer Syncing Command Complete')
        ->assertExitCode(0)
        ->run();
});
