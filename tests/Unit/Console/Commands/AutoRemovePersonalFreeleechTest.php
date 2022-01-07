<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRemovePersonalFreeleech
 */
class AutoRemovePersonalFreeleechTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:remove_personal_freeleech')
            ->expectsOutput('Automated Removal User Personal Freeleech Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
