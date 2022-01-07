<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\SetCache
 */
class SetCacheTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('set:all_cache')
            ->expectsOutput('Setting several common cache\'s ...')
            ->assertExitCode(0)
            ->run();
    }
}
