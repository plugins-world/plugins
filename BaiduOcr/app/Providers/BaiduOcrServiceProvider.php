<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\BaiduOcr\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BaiduOcrServiceProvider extends BaseServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();

        $this->loadMigrationsFrom(dirname(__DIR__, 2) . '/database/migrations');

        $this->app->register(RouteServiceProvider::class);

        // Event::listen(UserCreated::class, UserCreatedListener::class);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // if ($this->app->runningInConsole()) {
            $this->app->register(CommandServiceProvider::class);
        // }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2) . '/config/baidu-ocr.php', 'baidu-ocr'
        );
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $this->loadViewsFrom(dirname(__DIR__, 2) . '/resources/views', 'BaiduOcr');
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(dirname(__DIR__, 2) . '/resources/lang', 'BaiduOcr');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
