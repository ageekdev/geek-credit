<?php

namespace Ageekdev\GeekCredit\Tests;

use Ageekdev\GeekCredit\GeekCreditServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            GeekCreditServiceProvider::class,
        ];
    }
}
