<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\ClsLogger\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $commandsDirectory = dirname(__DIR__) . '/Console/Commands';
        if (File::exists($commandsDirectory)) {
            $this->load($commandsDirectory);
        }
    }

    /**
     * Register all of the commands in the given directory.
     *
     * @param array|string $paths
     */
    protected function load($paths): void
    {
        $paths = array_unique(Arr::wrap($paths));

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        $commands = [];
        foreach ((new Finder)->in($paths)->files() as $command) {
            $commandClass = Str::before(self::class, 'Providers\\') . 'Console\\Commands\\' . str_replace('.php', '', $command->getBasename());
            if (class_exists($commandClass)) {
                $commands[] = $commandClass;
            }
        }

        $this->commands($commands);
    }
}
