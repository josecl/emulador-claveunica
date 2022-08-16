<?php

declare(strict_types=1);

namespace Josecl\EmuladorClaveunica;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class EmuladorClaveunicaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/emulador-claveunica.php', 'emulador-claveunica');
    }

    public function boot(): void
    {
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/emulador-claveunica.php' => config_path('emulador-claveunica.php')], 'emulador-claveunica');
        }
    }

    protected function registerRoutes(): void
    {
        if (! config('emulador-claveunica.enabled')) {
            return;
        }

        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        });
    }

    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('emulador-claveunica.prefix'),
            'middleware' => config('emulador-claveunica.middleware'),
        ];
    }

//    private function registerBlade(): void
//    {
//        $this->loadViewsFrom(__DIR__.'/../resources/views', 'emulador-claveunica');
//    }

//        Route::prefix('api')
//            ->middleware('api')
//            ->group(__DIR__.'/routes.php');
}
