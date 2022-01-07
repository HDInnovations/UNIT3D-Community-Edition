<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoDeactivateWarning
 */
class AutoDeactivateWarningTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:deactivate_warning')
            ->expectsOutput('Automated Warning Deativation Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
