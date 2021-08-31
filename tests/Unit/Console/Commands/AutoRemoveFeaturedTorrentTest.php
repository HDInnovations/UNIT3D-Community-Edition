<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoRemoveFeaturedTorrent
 */
it('runs successfully', function () {
    $this->artisan('auto:remove_featured_torrent')
        ->expectsOutput('Automated Removal Featured Torrents Command Complete')
        ->assertExitCode(0)
        ->run();
});
