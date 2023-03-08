<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\SsoClient\Support;

use MouYong\LaravelConfig\Models\Config;

class Installer
{
    protected $config = [
        // [
        //     'item_tag' => 'sso_client',
        //     'item_key' => 'access_key',
        //     'item_type' => 'string',
        //     'item_value' => null,
        // ],
        [
            'item_tag' => 'sso_client',
            'item_key' => 'sso_server_host',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'sso_client',
            'item_key' => 'sso_update_userinfo_service',
            'item_type' => 'string',
            'item_value' => null,
        ],
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
        Config::addKeyValues($this->config);
        // $this->process(function ($configItem) {
        //     // add config
        // });
    }

    /// plugin uninstall
    public function uninstall()
    {
        Config::removeKeyValues($this->config);
        // $this->process(function ($configItem) {
        //     // remove config
        // });
    }
}
