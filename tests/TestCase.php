<?php

namespace Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JMac\Testing\Traits\AdditionalAssertions;

abstract class TestCase extends BaseTestCase
{
    use AdditionalAssertions;
    use CreatesApplication;
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // For LARAVEL_START used in sub-footer
        if (!\defined('LARAVEL_START')) {
            \define('LARAVEL_START', microtime(true));
        }
    }
}
