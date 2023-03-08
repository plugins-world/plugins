<?php

namespace Plugins\LaravelQiNiu\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseServiceProvider;

class RouteServiceProvider extends BaseServiceProvider
{
    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $middleware = array_merge(config('laravel-qiniu.api_middleware', []), ['web']);

        Route::middleware($middleware)
            ->group(dirname(__DIR__, 2) . '/routes/web.php');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $middleware = array_merge(config('laravel-qiniu.api_middleware', []), ['api']);

        Route::prefix('api')
            ->middleware($middleware)
            ->group(dirname(__DIR__, 2) . '/routes/api.php');
    }
}
