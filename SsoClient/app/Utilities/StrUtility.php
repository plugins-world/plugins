<?php

namespace Plugins\SsoClient\Utilities;

use MouYong\LaravelConfig\Models\Config;

class StrUtility
{
    public static function getSsoServerUrl(?string $url)
    {
        if (!$url) {
            return null;
        }

        $serverHost = Config::getValueByKey('sso_server_host') ?? '';
        if (!$serverHost) {
            \info('未配置 sso_server_host');
            return null;
        }

        $serverHost = rtrim($serverHost, '/');
        $getSsoServerUrl = $serverHost . '/'. ltrim($url, '/');

        return $getSsoServerUrl;
    }
}
