<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRewardResurrection
 */
class AutoRewardResurrectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:reward_resurrection')
            ->expectsOutput('Automated Reward Resurrections Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
