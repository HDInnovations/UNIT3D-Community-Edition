<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoWarning
 */
class AutoWarningTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:warning')
            ->expectsOutput('Automated User Warning Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
