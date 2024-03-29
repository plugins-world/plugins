<?php

namespace Plugins\WechatLogin\Utilities;

use EasyWeChat\OfficialAccount\Application as OfficialAccountApplication;
use EasyWeChat\MiniApp\Application as MiniAppApplication;
use EasyWeChat\OpenPlatform\Application as OpenPlatformApplication;
use Plugins\LaravelConfig\Models\Config;

class WechatUtility
{
    const TYPE_OFFICIAL_ACCOUNT = 'official_account';
    const TYPE_MINI_PROGRAM = 'mini_program';
    const TYPE_OPEN_PLATFORM = 'open_platform';
    const TPYE_MAP = [
        WechatUtility::TYPE_OFFICIAL_ACCOUNT => '微信公众号',
        WechatUtility::TYPE_MINI_PROGRAM => '微信小程序',
        WechatUtility::TYPE_OPEN_PLATFORM => '微信开放平台',
    ];

    public static function getConfig(string $type = null, string $appId = null): ?array
    {
        if (!array_key_exists($type, WechatUtility::TPYE_MAP)) {
            return null;
        }

        $itemKey = "wechat_login_{$type}";

        $itemValue = Config::getValueByKey($itemKey);
        if (empty($itemValue)) {
            return null;
        }

        // todo: 重构为单用户同时支持测试环境 appid, 正式环境 appid
        $appIdConfig = collect($itemValue)->where('appId', $appId)->first();
        if (empty($itemValue)) {
            return null;
        }

        $config = match ($type) {
            default => [],
            WechatUtility::TYPE_OFFICIAL_ACCOUNT => [
                'app_id' => $itemValue['appId'],
                'secret' => $itemValue['appSecret'],
            ],
            WechatUtility::TYPE_MINI_PROGRAM => [
                'app_id' => $itemValue['appId'],
                'secret' => $itemValue['appSecret'],
            ],
            WechatUtility::TYPE_OPEN_PLATFORM => [
                'app_id' => $itemValue['appId'],
                'secret' => $itemValue['appSecret'],
            ],
        };

        if (empty($config['app_id'])) {
            throw new \RuntimeException("请配置平台：{$type} 的 app_id: {$appId} 相关信息");
        }

        if ($appId && $config['app_id'] !== $appId) {
            throw new \RuntimeException("配置平台：{$type} 的 app_id: {$appId} 与系统记录信息不匹配");
        }

        /** @see https://easywechat.com/6.x/mini-app/index.html */
        $httpConfig = [
            'throw'  => false, // 状态码非 200、300 时是否抛出异常，默认为开启
            'timeout' => 5.0,
            // 'base_uri' => 'https://api.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri

            'retry' => true, // 使用默认重试配置
            //  'retry' => [
            //      // 仅以下状态码重试
            //      'http_codes' => [429, 500]
            //       // 最大重试次数
            //      'max_retries' => 3,
            //      // 请求间隔 (毫秒)
            //      'delay' => 1000,
            //      // 如果设置，每次重试的等待时间都会增加这个系数
            //      // (例如. 首次:1000ms; 第二次: 3 * 1000ms; etc.)
            //      'multiplier' => 3
            //  ],
        ];
        $config['http'] = $httpConfig;

        return $config;
    }

    public static function checkConfigAvaliable(?string $systemConfigAppId = null, ?string $clientAppId = null)
    {
        throw_if(!$systemConfigAppId, '系统配置错误，未配置服务端的 AppId');
        throw_if(!$clientAppId, '系统配置错误，客户端的 AppId 获取失败');
        throw_if($systemConfigAppId !== $clientAppId, '系统配置错误， AppId 不匹配');
    }

    public static function getApp(string $type = null, string $appId = null): null|OfficialAccountApplication|MiniAppApplication|OpenPlatformApplication
    {
        if (!array_key_exists($type, WechatUtility::TPYE_MAP)) {
            return null;
        }

        $config = WechatUtility::getConfig($type, $appId);
        if (!$config) {
            return null;
        }

        WechatUtility::checkConfigAvaliable($config['app_id'], $appId);

        $app = match ($type) {
            default => null,
            WechatUtility::TYPE_OFFICIAL_ACCOUNT => new OfficialAccountApplication($config),
            WechatUtility::TYPE_MINI_PROGRAM => new MiniAppApplication($config),
            WechatUtility::TYPE_OPEN_PLATFORM => new OpenPlatformApplication($config),
        };

        return $app;
    }

    public static function findTenantConfig(\Illuminate\Database\Eloquent\Model $tenant, string $itemKey)
    {
        return null;
    }

    public static function formatConfig(\Illuminate\Database\Eloquent\Model $tenant, string $type = null, string $appId = null): ?array
    {
        if (!array_key_exists($type, WechatUtility::TPYE_MAP)) {
            return null;
        }

        $itemKey = "wechat_login_{$type}";

        $config = static::findTenantConfig($tenant, $itemKey);
        if (!$config) {
            return new \RuntimeException("请实现查找 tenant config 的方式");
        }

        $itemValue = $config->item_value;
        if (empty($itemValue)) {
            return null;
        }

        $config = match ($type) {
            default => [],
            WechatUtility::TYPE_OFFICIAL_ACCOUNT => [
                'app_id' => $itemValue['appId'],
                'secret' => $itemValue['appSecret'],
            ],
            WechatUtility::TYPE_MINI_PROGRAM => [
                'app_id' => $itemValue['appId'],
                'secret' => $itemValue['appSecret'],
            ],
            WechatUtility::TYPE_OPEN_PLATFORM => [
                'app_id' => $itemValue['appId'],
                'secret' => $itemValue['appSecret'],
            ],
        };

        if (empty($config['app_id'])) {
            throw new \RuntimeException("请配置平台：{$type} 的 app_id: {$appId} 相关信息");
        }

        if ($appId && $config['app_id'] !== $appId) {
            throw new \RuntimeException("配置平台：{$type} 的 app_id: {$appId} 与系统记录信息不匹配");
        }

        /** @see https://easywechat.com/6.x/mini-app/index.html */
        $httpConfig = [
            'throw'  => false, // 状态码非 200、300 时是否抛出异常，默认为开启
            'timeout' => 5.0,
            // 'base_uri' => 'https://api.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri

            'retry' => true, // 使用默认重试配置
            //  'retry' => [
            //      // 仅以下状态码重试
            //      'http_codes' => [429, 500]
            //       // 最大重试次数
            //      'max_retries' => 3,
            //      // 请求间隔 (毫秒)
            //      'delay' => 1000,
            //      // 如果设置，每次重试的等待时间都会增加这个系数
            //      // (例如. 首次:1000ms; 第二次: 3 * 1000ms; etc.)
            //      'multiplier' => 3
            //  ],
        ];
        $config['http'] = $httpConfig;

        return $config;
    }

    public static function getTenantApp(\Illuminate\Database\Eloquent\Model|null $tenant = null, string $type = null, string $appId = null)
    {
        if (is_null($tenant)) {
            return static::getApp($type, $appId);
        }

        if (!array_key_exists($type, WechatUtility::TPYE_MAP)) {
            return null;
        }

        $config = static::formatConfig($tenant, $type, $appId);
        if (!$config) {
            return null;
        }

        WechatUtility::checkConfigAvaliable($config['app_id'], $appId);

        $app = match ($type) {
            default => null,
            WechatUtility::TYPE_OFFICIAL_ACCOUNT => new OfficialAccountApplication($config),
            WechatUtility::TYPE_MINI_PROGRAM => new MiniAppApplication($config),
            WechatUtility::TYPE_OPEN_PLATFORM => new OpenPlatformApplication($config),
        };

        return $app;
    }

    public static function checkCodeUsed(\Throwable $exception, string $code)
    {
        $message = $exception->getMessage();

        $codeUsed = str_contains($message, 'code been used');

        throw_if($codeUsed, "登录 code: {$code} 失效，请重新获取");
    }
}
