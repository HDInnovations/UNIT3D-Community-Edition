<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\SetCache
 */
class SetCacheTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_successfully(): void
    {
        $this->artisan('set:all_cache')
            ->expectsOutput('Setting several common cache\'s ...')
            ->assertExitCode(0)
            ->run();
    }
}
