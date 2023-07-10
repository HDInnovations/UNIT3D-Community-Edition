<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoDeactivateWarning
 */
final class AutoDeactivateWarningTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:deactivate_warning')
            ->expectsOutput('Automated Warning Deativation Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
