<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\SystemAuthorization\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class SystemAuthorizationServiceProvider extends BaseServiceProvider
{
    use \ZhenMu\Support\Traits\ModelResultTrait;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerResultToBuilder();

        $this->loadMigrationsFrom(dirname(__DIR__, 2) . '/database/migrations');

        $this->app->register(RouteServiceProvider::class);

        // Event::listen(UserCreated::class, UserCreatedListener::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // if ($this->app->runningInConsole()) {
            $this->app->register(CommandServiceProvider::class);
        // }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2) . '/config/system-authorization.php', 'system-authorization'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $this->loadViewsFrom(dirname(__DIR__, 2) . '/resources/views', 'SystemAuthorization');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $this->loadTranslationsFrom(dirname(__DIR__, 2) . '/resources/lang', 'SystemAuthorization');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
