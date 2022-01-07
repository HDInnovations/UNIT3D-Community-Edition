<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoCorrectHistory
 */
class AutoCorrectHistoryTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:correct_history')
            ->expectsOutput('Automated History Record Correction Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
