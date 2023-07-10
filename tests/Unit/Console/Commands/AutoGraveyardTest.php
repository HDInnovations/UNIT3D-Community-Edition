<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\AutoGraveyard
 */
class AutoGraveyardTest extends TestCase
{
    #[Test]
    public function it_runs_successfully(): void
    {
        $this->artisan('auto:graveyard')
            ->expectsOutput('Automated Graveyard Rewards Command Complete')
            ->assertExitCode(0)
            ->run();
    }
}
