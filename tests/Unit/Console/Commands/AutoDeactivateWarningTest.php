<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoDeactivateWarning
 */
it('runs successfully', function () {
    $this->artisan('auto:deactivate_warning')
        ->expectsOutput('Automated Warning Deativation Command Complete')
        ->assertExitCode(0)
        ->run();
});
