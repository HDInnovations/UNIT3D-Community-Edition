<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\ClearCache
 */
final class ClearCacheTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('clear:all_cache')
            ->expectsOutput('Clearing several common cache\'s ...')
            ->assertExitCode(0)
            ->run();
    }
}
