<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRemoveFeaturedTorrent
 */
class AutoRemoveFeaturedTorrentTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:remove_featured_torrent')
            ->expectsOutput('Automated Removal Featured Torrents Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
