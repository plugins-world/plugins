<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\LaravelQiNiu\Support;

use MouYong\LaravelConfig\Models\Config;

class Installer
{
    protected $config = [
        [
            'item_tag' => 'laravel-qiniu',
            'item_key' => 'is_central_config',
            'item_type' => 'bool',
            'item_value' => false,
        ],
        [
            'item_tag' => 'laravel-qiniu',
            'item_key' => 'laravel-qiniu.web_middleware',
            'item_type' => 'array',
            'item_value' => ['api'],
        ],
        [
            'item_tag' => 'laravel-qiniu',
            'item_key' => 'laravel-qiniu.api_middleware',
            'item_type' => 'array',
            'item_value' => ['api'],
        ],
        [
            'item_tag' => 'laravel-qiniu',
            'item_key' => 'access_key',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'laravel-qiniu',
            'item_key' => 'secret_key',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'laravel-qiniu',
            'item_key' => 'bucket',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'laravel-qiniu',
            'item_key' => 'domain',
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
    }

    /// plugin uninstall
    public function uninstall()
    {
        Config::removeKeyValues($this->config);
    }
}
