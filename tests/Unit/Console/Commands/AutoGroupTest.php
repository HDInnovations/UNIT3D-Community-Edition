<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoGroup
 */
final class AutoGroupTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:group')
            ->expectsOutput('Automated User Group Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
