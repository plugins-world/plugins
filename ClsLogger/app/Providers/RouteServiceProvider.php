<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\ClsLogger\Providers;

use Fresns\MarketManager\Models\Plugin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseServiceProvider;

class RouteServiceProvider extends BaseServiceProvider
{
    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $host = null;

        // try {
        //     if (class_exists(Plugin::class)) {
        //         $fskey = 'ClsLogger';
        //         $cacheKey = "{$fskey}_model";

        //         $pluginModel = Cache::get($cacheKey);
        //         if (empty($pluginModel)) {
        //             $pluginModel = Plugin::withTrashed()->where('fskey', $fskey)->first();

        //             Cache::put($cacheKey, $pluginModel, now()->addMinutes(30));
        //         }

        //         $pluginHost = $pluginModel?->plugin_host ?? '';

        //         $host = str_replace(['http://', 'https://'], '', rtrim($pluginHost, '/'));
        //     }
        // } catch (\Throwable $e) {
        //     info("get plugin host failed: " . $e->getMessage());
        // }

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
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(dirname(__DIR__, 2) . '/routes/web.php');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')->name('api.')->middleware('api')->group(dirname(__DIR__, 2) . '/routes/api.php');
    }
}
