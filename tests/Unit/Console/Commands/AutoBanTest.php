<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoBan
 */
class AutoBanTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully()
    {
        $this->artisan('auto:ban')
            ->expectsOutput('Automated User Banning Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
