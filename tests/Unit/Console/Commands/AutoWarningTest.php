<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoWarning
 */
it('runs successfully', function () {
    $this->artisan('auto:warning')
        ->expectsOutput('Automated User Warning Command Complete')
        ->assertExitCode(0)
        ->run();
});
