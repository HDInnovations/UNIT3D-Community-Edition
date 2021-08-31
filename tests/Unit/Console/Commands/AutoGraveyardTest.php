<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoGraveyard
 */
it('runs successfully', function () {
    $this->artisan('auto:graveyard')
        ->expectsOutput('Automated Graveyard Rewards Command Complete')
        ->assertExitCode(0)
        ->run();
});
