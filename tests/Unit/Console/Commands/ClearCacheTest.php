<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\ClearCache
 */
it('runs successfully', function () {
    $this->artisan('clear:all_cache')
        ->expectsOutput('Clearing several common cache\'s ...')
        ->assertExitCode(0)
        ->run();
});
