<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoPreWarning
 */
class AutoPreWarningTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:prewarning')
            ->expectsOutput('Automated User Pre-Warning Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
