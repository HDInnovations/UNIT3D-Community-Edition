<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRemoveFeaturedTorrent
 */
class AutoRemoveFeaturedTorrentTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:remove_featured_torrent')
            ->expectsOutput('Automated Removal Featured Torrents Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
