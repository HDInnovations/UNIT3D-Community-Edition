<?php

namespace Tests;

use JMac\Testing\Traits\HttpTestAssertions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, HttpTestAssertions;
}
