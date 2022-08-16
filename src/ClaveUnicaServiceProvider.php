<?php

declare(strict_types=1);

namespace Josecl\ClaveUnica;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ClaveUnicaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/claveunica.php', 'claveunica');
    }

    public function boot(): void
    {
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/claveunica.php' => config_path('claveunica.php')], 'claveunica');
        }
    }

    protected function registerRoutes(): void
    {
        if (! config('claveunica.enabled')) {
            return;
        }

        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        });
    }

    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('claveunica.prefix'),
            'middleware' => config('claveunica.middleware'),
        ];
    }

//    private function registerBlade(): void
//    {
//        $this->loadViewsFrom(__DIR__.'/../resources/views', 'claveunica');
//    }

//        Route::prefix('api')
//            ->middleware('api')
//            ->group(__DIR__.'/routes.php');
}
