<?php

namespace Plugins\BaiduOcr\Utilities;

use Plugins\LaravelConfig\Models\Config;

class OCRUtility
{
    public static function initConfig()
    {
        $config = Config::getValueByKey('ocr_config', 'baidu_ocr');
        if (!$config) {
            throw new \RuntimeException('请完善配置信息');
        }

        return $config;
    }

    public static function getOCR()
    {
        $config = OCRUtility::initConfig();

        $ocr = OCRConfigUtility::getInstance($config['api_key'], $config['secret_key'], $config['request_url']);

        return $ocr;
    }

    public static function request(string $method, string $action, array $params = [])
    {
        $resp = OCRUtility::getOCR()->request($method, $action, $params);

        return $resp;
    }
}
