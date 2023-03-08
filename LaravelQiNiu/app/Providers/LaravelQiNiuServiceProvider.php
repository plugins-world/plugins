<?php

namespace Plugins\LaravelQiNiu\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class LaravelQiNiuServiceProvider extends BaseServiceProvider
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

        // Event::listen(UserCreated::class, UserCreatedListener::class);

        if ($apiMiddleware = db_config_central('laravel-qiniu.api_middleware')) {
            config([
                'laravel-qiniu.api_middleware' => $apiMiddleware,
            ]);
        }

        $this->app->register(RouteServiceProvider::class);
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
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2) . '/config/laravel-qiniu.php',
            'laravel-qiniu'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__, 2) . '/config/filesystems.php',
            'laravel-qiniu-filesystems'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $this->loadViewsFrom(dirname(__DIR__, 2) . '/resources/views', 'LaravelQiNiu');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $this->loadTranslationsFrom(dirname(__DIR__, 2) . '/resources/lang', 'LaravelQiNiu');
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
