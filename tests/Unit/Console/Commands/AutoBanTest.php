<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoBan
 */
it('runs successfully', function () {
    $this->artisan('auto:ban')
        ->expectsOutput('Automated User Banning Command Complete')
        ->assertExitCode(0)
        ->run();
});
