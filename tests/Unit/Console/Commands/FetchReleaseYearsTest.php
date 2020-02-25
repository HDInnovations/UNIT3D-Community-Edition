<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\FetchReleaseYears
 */
class FetchReleaseYearsTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully()
    {
        $this->artisan('fetch:release_years')
            ->expectsOutput('Torrent Release Year Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
