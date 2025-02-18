<?php

namespace Plugins\ClsLogger\Providers;

use Illuminate\Support\ServiceProvider;

class ClsLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $channels = config('logging.channels');
        $channels['cls'] = [
            'driver' => 'monolog',
            'handler' => \Plugins\ClsLogger\Logging\ClsLoggerHandler::class,

            // or

            // 'driver' => 'custom',
            // 'via' => \Plugins\ClsLogger\Logging\ClsLogger::class,
        ];

        config(['logging.channels' => $channels]);
    }
}
