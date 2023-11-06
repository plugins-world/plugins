<?php

namespace Plugins\EasyMap\Utilities;

use Plugins\LaravelConfig\Models\Config;

class MapUtility
{
    public static function initConfig()
    {
        $config = [];

        $mapDefaultPlatform = Config::getValueByKey('map_default_platform', 'easy_map');

        if ($mapDefaultPlatform) {
            $config = Config::getValueByKey($mapDefaultPlatform, 'easy_map');
        }

        $config['platform'] = $mapDefaultPlatform;

        return $config;
    }

    public static function getMap()
    {
        $config = MapUtility::initConfig();

        $map = match ($config['platform']) {
            AMapUtility::PLATFORM => AMapUtility::getInstance($config['key'], $config['request_url']),
            default => null
        };

        return $map;
    }

    public static function request(string $method, string $action, array $params = [])
    {
        $resp = MapUtility::getMap()->request($method, $action, $params);

        return $resp;
    }
}
