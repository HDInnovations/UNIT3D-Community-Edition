<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoBonAllocation
 */
it('runs successfully', function () {
    $this->artisan('auto:bon_allocation')
        ->expectsOutput('Automated BON Allocation Command Complete')
        ->assertExitCode(0)
        ->run();
});
