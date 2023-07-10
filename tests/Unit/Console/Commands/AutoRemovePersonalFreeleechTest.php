<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRemovePersonalFreeleech
 */
final class AutoRemovePersonalFreeleechTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:remove_personal_freeleech')
            ->expectsOutput('Automated Removal User Personal Freeleech Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
