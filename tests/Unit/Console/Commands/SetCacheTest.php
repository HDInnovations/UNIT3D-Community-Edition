<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\SetCache
 */
it('runs successfully', function () {
    $this->artisan('set:all_cache')
        ->expectsOutput('Setting several common cache\'s ...')
        ->assertExitCode(0)
        ->run();
});
