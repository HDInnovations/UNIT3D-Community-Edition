<?php

use Tests\TestCase;

uses(TestCase::class);

/**
 * @see \App\Console\Commands\AutoRemovePersonalFreeleech
 */
it('runs successfully', function () {
    $this->artisan('auto:remove_personal_freeleech')
        ->expectsOutput('Automated Removal User Personal Freeleech Command Complete')
        ->assertExitCode(0)
        ->run();
});
