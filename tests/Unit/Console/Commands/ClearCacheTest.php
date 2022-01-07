<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\ClearCache
 */
class ClearCacheTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('clear:all_cache')
            ->expectsOutput('Clearing several common cache\'s ...')
            ->assertExitCode(0)
            ->run();
    }
}
