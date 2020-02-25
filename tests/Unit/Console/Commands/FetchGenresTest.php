<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\FetchGenres
 */
class FetchGenresTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully()
    {
        $this->artisan('fetch:genres')
            ->expectsOutput('Torrent Genres Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
