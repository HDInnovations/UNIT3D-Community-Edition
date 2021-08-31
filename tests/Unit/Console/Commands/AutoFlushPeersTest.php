<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoFlushPeers
 */
it('runs successfully', function () {
    $this->artisan('auto:flush_peers')
        ->expectsOutput('Automated Flush Ghost Peers Command Complete')
        ->assertExitCode(0)
        ->run();
});
