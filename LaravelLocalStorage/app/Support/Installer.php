<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\LaravelLocalStorage\Support;

class Installer
{
    protected $config = [
        // [
        //     'item_tag' => 'laravel_local_storage',
        //     'item_key' => 'access_key',
        //     'item_type' => 'string',
        //     'item_value' => null,
        // ],
    ];

    public function process(callable $callable)
    {
        foreach ($this->config as $configItem) {
            $callable($configItem);
        }
    }

    // plugin install
    public function install()
    {
        $this->process(function ($configItem) {
            // add config
        });
    }

    /// plugin uninstall
    public function uninstall()
    {
        $this->process(function ($configItem) {
            // remove config
        });
    }
}
