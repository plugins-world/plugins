<?php

namespace Plugins\PayCenter\Utilities;

use Yansongda\Pay\Pay;
use Illuminate\Support\Facades\File;
use Plugins\LaravelConfig\Models\Config;

class PayUtility
{
    public static function init(string $type)
    {
        $configModel = Config::where('item_key', $type)->where('item_tag', 'pay_center')->first();

        $config = $configModel->item_value ?? [];
        $config['mch_id'] = strval($config['mch_id']);
        $config['mch_secret_cert'] = base_path($config['mch_secret_cert']);
        $config['mch_public_cert_path'] = base_path($config['mch_public_cert_path']);

        throw_if(! is_file($config['mch_secret_cert']), '文件 apiclient_key.pem 不存在');
        throw_if(! is_file($config['mch_public_cert_path']), '文件 apiclient_cert.pem 不存在');
        foreach($config['wechat_public_cert_path'] ?? [] as $serialNo => $file) {
            $config['wechat_public_cert_path'][$serialNo] = base_path($file);

            throw_if(! is_file($config['wechat_public_cert_path'][$serialNo]), "文件 {$serialNo}.crt 不存在");
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
            $data[$serialNo] = str_replace(base_path(). '/', '', $filepath);
        }

        $configModel = Config::where('item_key', 'pay_center_wechatpay')->where('item_tag', 'pay_center')->first();
        $newConfig = $configModel->item_value;
        $newConfig['wechat_public_cert_path'] = $data;

        $configModel->item_value = $newConfig;
        $configModel->save();
    }

    public static function callback(string $type)
    {
        PayUtility::init($type);

        return Pay::wechat()->callback();
    }

    public static function success(string $type)
    {
        PayUtility::init($type);

        return Pay::wechat()->success();
    }
}
