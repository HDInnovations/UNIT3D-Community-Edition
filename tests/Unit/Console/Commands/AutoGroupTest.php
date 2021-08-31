<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoGroup
 */
it('runs successfully', function () {
    $this->artisan('auto:group')
        ->expectsOutput('Automated User Group Command Complete')
        ->assertExitCode(0)
        ->run();
});
