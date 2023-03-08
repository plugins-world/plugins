<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\SsoServer\Support;

use MouYong\LaravelConfig\Models\Config;

class Installer
{
    const RSA_CONFIG = array(
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    );

    protected $config = [
        // [
        //     'item_tag' => 'sso_server',
        //     'item_key' => 'access_key',
        //     'item_type' => 'string',
        //     'item_value' => null,
        // ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'rsa_config',
            'item_type' => 'array',
            'item_value' => Installer::RSA_CONFIG,
        ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'rsa_public_key',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'rsa_private_key',
            'item_type' => 'string',
            'item_value' => null,
        ],

        [
            'item_tag' => 'sso_server',
            'item_key' => 'sso_cookie_prefix',
            'item_type' => 'string',
            'item_value' => null, // 默认值
        ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'sso_login_url',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'sso_logout_url',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'sso_register_url',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'sso_index_url',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'sso_service_url',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'sso_validate_url',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'sso_get_user_info_api',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'sso_server',
            'item_key' => 'sso_get_public_key_api',
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
        $rsaInfo = \ZhenMu\Support\Utils\RSA::generate(Installer::RSA_CONFIG);

        $this->process(function ($configItem) use ($rsaInfo) {
            if ($configItem['item_key'] == 'rsa_public_key') {
                $configItem['item_value'] = \ZhenMu\Support\Utils\RSA::singleLinePublicKey($rsaInfo['publicKey']);
            }

            if ($configItem['item_key'] == 'rsa_private_key') {
                $configItem['item_value'] = \ZhenMu\Support\Utils\RSA::singleLinePrivateKey($rsaInfo['privateKey']);
            }

            Config::addConfig($configItem);
        });
    }

    /// plugin uninstall
    public function uninstall()
    {
        Config::removeKeyValues($this->config);
        $this->process(function ($configItem) {
            // remove config
        });
    }
}
