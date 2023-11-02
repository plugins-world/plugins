<?php

namespace Plugins\EasySms\Utilities;

use Overtrue\EasySms\EasySms;
use Plugins\LaravelConfig\Models\Config;

class SmsUtility
{
    public static function initConfig()
    {
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    // 'qcloud',
                    // 'errorlog',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => storage_path('logs/easy-sms.log'),
                ],
            ],
        ];

        $gateway = Config::getValueByKey('sms_default_gateway', 'easy_sms');

        $gatewayConfig = null;
        if ($gateway) {
            $gatewayConfig = Config::getValueByKey($gateway, 'easy_sms');
            if ('qcloud' == $gateway) {
                $gatewayConfig['sdk_app_id'] = strval($gatewayConfig['sdk_app_id']);
            }

            $default = $config['gateways'][$gateway] ?? [];
            $config['default']['gateways'][] = $gateway;
            $config['gateways'][$gateway] = array_merge($default, $gatewayConfig);
        }

        return $config;
    }

    public static function getSms()
    {
        $config = SmsUtility::initConfig();

        return new EasySms($config);
    }

    public static function send($to, array $params = [])
    {
        try {
            $resp = SmsUtility::getSms()->send($to, $params);
        } catch (\Throwable $e) {
            SmsUtility::throwException($e);
        }

        return $resp;
    }

    protected static function throwException(\Throwable $e)
    {
        $message = $e->getMessage();
        
        if (method_exists($e, 'getExceptions')) {
            $message = '';

            foreach ($e->getExceptions() as $gateway => $exception) {
                $msg = sprintf("【%s】 code: %s, message: %s\n", $gateway, $exception->getCode(), $exception->getMessage());

                $message .= $msg;
            }
        }

        throw new \RuntimeException($message);
    }
}
