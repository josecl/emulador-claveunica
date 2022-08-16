<?php

declare(strict_types=1);

namespace Josecl\EmuladorClaveunica\Tests;

use Illuminate\Support\Facades\Http;
use Josecl\EmuladorClaveunica\EmuladorClaveunicaServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    protected function defineEnvironment($app): void
    {
//        $app['config']->set('app.name', config('claveunica'));
    }

    protected function getPackageProviders($app): array
    {
        return [
            EmuladorClaveunicaServiceProvider::class,
        ];
    }
}
