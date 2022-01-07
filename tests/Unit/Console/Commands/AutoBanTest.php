<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoBan
 */
class AutoBanTest extends TestCase
{
    public function testItRunsSuccessfully()
    {
        $this->artisan('auto:ban')
            ->expectsOutput('Automated User Banning Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
