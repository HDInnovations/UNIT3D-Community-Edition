<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoHighspeedTag
 */
it('runs successfully', function () {
    $this->artisan('auto:highspeed_tag')
        ->expectsOutput('Automated High Speed Torrents Command Complete')
        ->assertExitCode(0)
        ->run();
});
