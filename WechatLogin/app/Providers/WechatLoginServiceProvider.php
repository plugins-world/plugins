<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\WechatLogin\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class WechatLoginServiceProvider extends BaseServiceProvider
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
            dirname(__DIR__, 2) . '/config/wechat-login.php', 'wechat-login'
        );
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $this->loadViewsFrom(dirname(__DIR__, 2) . '/resources/views', 'WechatLogin');
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(dirname(__DIR__, 2) . '/resources/lang', 'WechatLogin');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
