<?php

namespace Plugins\LaravelLocalStorage\Providers;

use Fresns\PluginManager\Models\Plugin;
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
        try {
            $pluginHost = Plugin::findByFskey('Market')?->plugin_host;
            $host = str_replace(['http://', 'https://'], '', rtrim($pluginHost, '/'));
        } catch (\Throwable $e) {
            $host = null;
        }

        Route::group([
            'domain' => $host,
        ], function () {
            $this->mapApiRoutes();

            $this->mapWebRoutes();
        });
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
        Route::middleware('web')
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
        Route::prefix('api')
            ->middleware('api')
            ->group(dirname(__DIR__, 2) . '/routes/api.php');
    }
}
