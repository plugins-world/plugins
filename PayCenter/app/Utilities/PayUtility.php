<?php

namespace Plugins\PayCenter\Utilities;

use Yansongda\Pay\Pay;
use Illuminate\Support\Facades\File;
use Plugins\LaravelConfig\Models\Config;

class PayUtility
{
    public static function findConfig(string $initConfigKey)
    {
        $configModel = Config::where('item_key', $initConfigKey)->where('item_tag', 'pay_center')->first();

        return $configModel;
    }

    public static function findTenantConfig(\Illuminate\Database\Eloquent\Model $tenant, string $initConfigKey)
    {
        return null;
    }

    public static function init(string $initConfigKey)
    {
        $tenant = request()->attributes->get('tenant');
        if (!$tenant) {
            $configModel = static::findConfig($initConfigKey);
        } else {
            $configModel = static::findTenantConfig($tenant, $initConfigKey);
        }

        if (!$configModel) {
            throw new \RuntimeException('请配置支付信息');
        }

        $config = $configModel->item_value ?? [];
        $config['mch_id'] = strval($config['mch_id']);

        // mch_secret_cert: apiclient_key.pem
        if (
            str_ends_with($config['mch_secret_cert'], '.crt')
            || str_ends_with($config['mch_secret_cert'], '.pem')
        ) {
            $mch_secret_cert = base_path($config['mch_secret_cert']);
            $config['mch_secret_cert'] = @file_get_contents($mch_secret_cert);
        }

        // 替换文件内容，避免拼接内容时，重复拼接
        $config['mch_secret_cert'] = str_replace([
            "-----BEGIN PRIVATE KEY-----\n",
            "\n-----END PRIVATE KEY-----",
        ], '', $config['mch_secret_cert']);
        throw_if(empty($config['mch_secret_cert']), 'apiclient_key.pem 信息不存在, 请检查是否设置 mch_secret_cert');

        // mch_public_cert_path: apiclient_cert.pem
        if (
            str_ends_with($config['mch_public_cert_path'], '.cer')
            || str_ends_with($config['mch_public_cert_path'], '.crt')
            || str_ends_with($config['mch_public_cert_path'], '.pem')
        ) {
            $mch_public_cert_path = base_path($config['mch_public_cert_path']);
            $config['mch_public_cert_path'] = @file_get_contents($mch_public_cert_path);
        }
        throw_if(empty($config['mch_public_cert_path']), 'apiclient_cert.pem 信息不存在, 请检查是否设置 mch_public_cert_path');

        foreach ($config['wechat_public_cert_path'] ?? [] as $serialNo => $content) {
            if (str_starts_with($content, '---')) {
                $config['wechat_public_cert_path'][$serialNo] = $content;
            }
        }

        $payConfig = [
            'wechat' => [
                'default' => $config,
            ]
        ];

        Pay::config($payConfig);

        return $config;
    }

    public static function downloadWechatCert()
    {
        $savePath = storage_path('app/certs');
        File::ensureDirectoryExists($savePath);

        PayUtility::init('pay_center_wechatpay');

        $params = [
            '_config' => 'default' // 多租户配置时使用
        ];

        \Yansongda\Pay\get_wechat_public_certs($params, $savePath);
        $files = glob(storage_path('app/certs/*.crt'));

        $data = [];
        foreach ($files as $filepath) {
            $serialNo = pathinfo($filepath, PATHINFO_FILENAME);
            $content = file_get_contents($filepath);
            $data[$serialNo] = $content;
        }

        $configModel = Config::where('item_key', 'pay_center_wechatpay')->where('item_tag', 'pay_center')->first();
        $newConfig = $configModel->item_value;
        $newConfig['wechat_public_cert_path'] = $data;

        $configModel->item_value = $newConfig;
        $configModel->save();
    }

    public static function query(array $params, string $payPlatform, string $initConfigKey)
    {
        static::init($initConfigKey);

        return Pay::{$payPlatform}()->query($params);
    }

    public static function refund(array $params, string $payPlatform, string $initConfigKey)
    {
        static::init($initConfigKey);

        return Pay::{$payPlatform}()->refund($params);
    }

    public static function close(array $params, string $payPlatform, string $initConfigKey)
    {
        static::init($initConfigKey);

        return Pay::{$payPlatform}()->close($params);
    }

    public static function callback(string $payPlatform, string $initConfigKey)
    {
        static::init($initConfigKey);

        return Pay::{$payPlatform}()->callback();
    }

    public static function success(string $payPlatform, string $initConfigKey)
    {
        static::init($initConfigKey);

        return Pay::{$payPlatform}()->success();
    }
}
