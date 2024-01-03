<?php

namespace Plugins\BaiduFaceOcr\Utilities;

use Plugins\LaravelConfig\Models\Config;

class FaceOCRUtility
{
    public static function initConfig()
    {
        $config = Config::getValueByKey('face_ocr_config', 'baidu_face_ocr');
        if (!$config) {
            throw new \RuntimeException('请配置方案信息');
        }

        return $config;
    }

    public static function getOCR()
    {
        $config = FaceOCRUtility::initConfig();

        $ocr = FaceOCRConfigUtility::getInstance($config['api_key'], $config['secret_key'], $config['request_url']);

        return $ocr;
    }

    public static function request(string $method, string $action, array $params = [])
    {
        $resp = FaceOCRUtility::getOCR()->request($method, $action, $params);

        return $resp;
    }
}
