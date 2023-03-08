<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\SystemAuthorization\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Plugins\SystemAuthorization\Console\Commands\AuthCodeExpiredCheck;

class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * Register any services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->resolving(Schedule::class, function ($schedule) {
            $this->schedule($schedule);
        });
    }

    /**
     * Prepare schedule from tasks.
     *
     * @param  Schedule  $schedule
     */
    public function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
         $schedule->command(AuthCodeExpiredCheck::class)->dailyAt('02:00');
    }
}
