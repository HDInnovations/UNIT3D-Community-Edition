<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoRecycleAudits
 */
final class AutoRecycleAuditsTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:recycle_activity_log')
            ->expectsOutput('Automated Purge Old Audits Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
