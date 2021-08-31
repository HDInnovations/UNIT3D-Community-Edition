<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoPreWarning
 */
it('runs successfully', function () {
    $this->artisan('auto:prewarning')
        ->expectsOutput('Automated User Pre-Warning Command Complete')
        ->assertExitCode(0)
        ->run();
});
