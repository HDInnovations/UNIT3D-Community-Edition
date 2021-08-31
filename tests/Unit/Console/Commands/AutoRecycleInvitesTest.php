<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoRecycleInvites
 */
it('runs successfully', function () {
    $this->artisan('auto:recycle_invites')
        ->expectsOutput('Automated Purge Unaccepted Invites Command Complete')
        ->assertExitCode(0)
        ->run();
});
