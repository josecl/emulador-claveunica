<?php

declare(strict_types=1);

namespace Josecl\EmuladorClaveunica\Tests;

use Josecl\EmuladorClaveunica\EmuladorClaveunicaServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            EmuladorClaveunicaServiceProvider::class,
        ];
    }
}
