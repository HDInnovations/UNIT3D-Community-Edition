<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoPreWarning
 */
final class AutoPreWarningTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:prewarning')
            ->expectsOutput('Automated User Pre-Warning Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
