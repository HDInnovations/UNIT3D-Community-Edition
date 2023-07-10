<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRemoveFeaturedTorrent
 */
final class AutoRemoveFeaturedTorrentTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:remove_featured_torrent')
            ->expectsOutput('Automated Removal Featured Torrents Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
