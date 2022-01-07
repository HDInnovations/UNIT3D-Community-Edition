<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoBonAllocation
 */
class AutoBonAllocationTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:bon_allocation')
            ->expectsOutput('Automated BON Allocation Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
