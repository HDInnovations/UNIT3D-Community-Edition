<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoCorrectHistory
 */
it('runs successfully', function () {
    $this->artisan('auto:correct_history')
        ->expectsOutput('Automated History Record Correction Command Complete')
        ->assertExitCode(0)
        ->run();
});
