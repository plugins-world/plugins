<?php

namespace Plugins\LaravelJwtAuth\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class LaravelJwtAuthServiceProvider extends BaseServiceProvider
{
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

        $this->loadMigrationsFrom(dirname(__DIR__, 2) . '/database/migrations');

        $this->initJwtConfig();
        $this->initAuthConfig();

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
        if ($this->app->runningInConsole()) {
            $this->app->register(CommandServiceProvider::class);
        }

        $this->registerPublish();
    }

    public function registerPublish()
    {
        $this->publishes([
            dirname(__DIR__, 2) . '/config/laravel-jwt-auth.php' => config_path('laravel-jwt-auth.php'),
        ], 'laravel-jwt-auth-config');

        $this->publishes([
            dirname(__DIR__, 2) . '/database/migrations' => database_path('migrations'),
        ], 'laravel-jwt-auth-migration');
    }

    public function initAuthConfig()
    {
        $auth = config('auth');

        foreach (config('laravel-jwt-auth.auth.guards') as $key => $value) {
            Arr::set($auth, "guards.{$key}", $value);
        }

        foreach (config('laravel-jwt-auth.auth.providers') as $key => $value) {
            Arr::set($auth, "providers.{$key}", $value);
        }

        config([
            'auth' => $auth,
        ]);
    }

    public function initJwtConfig()
    {
        $jwt = config('jwt');

        $jwtConfig = config('laravel-jwt-auth.jwt');

        Arr::set($jwt, "ttl", $jwtConfig['ttl']);
        Arr::set($jwt, "refresh_ttl", $jwtConfig['refresh_ttl']);

        config([
            'jwt' => $jwt,
        ]);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2) . '/config/laravel-jwt-auth.php',
            'laravel-jwt-auth'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $this->loadViewsFrom(dirname(__DIR__, 2) . '/resources/views', 'LaravelJwtAuth');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $this->loadTranslationsFrom(dirname(__DIR__, 2) . '/resources/lang', 'LaravelJwtAuth');
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
