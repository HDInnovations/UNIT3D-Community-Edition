<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JMac\Testing\Traits\HttpTestAssertions;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use HttpTestAssertions;
    use RefreshDatabase;
}
