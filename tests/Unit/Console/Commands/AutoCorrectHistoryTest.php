<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoCorrectHistory
 */
final class AutoCorrectHistoryTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:correct_history')
            ->expectsOutput('Automated History Record Correction Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
