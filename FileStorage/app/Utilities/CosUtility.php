<?php

namespace Plugins\FileStorage\Utilities;

use Illuminate\Support\Facades\Storage;
use Plugins\LaravelConfig\Models\Config;
use Illuminate\Filesystem\FilesystemAdapter;

class CosUtility
{
    const DISK_KEY = 'cos';
    
    public static function getConfig(): array
    {
        $itemKeys = [
            'is_use_center_config',
            'app_id',
            'secret_id',
            'secret_key',
            'reigon',
            'bucket',
            'signed_url',
            'use_https',
            'domain',
            'cdn',
        ];

        return Config::getValueByKeys($itemKeys);
    }

    public static function dynamicsConfig()
    {
        $config = CosUtility::getConfig();
        if (is_tenant_mode() && $config['is_use_center_config']) {
            $config = central(fn () => CosUtility::getConfig());
        }

        $cosConfig['driver'] = CosUtility::DISK_KEY;
        $cosConfig['app_id'] = $config['app_id'];
        $cosConfig['secret_id'] = $config['secret_id'];
        $cosConfig['secret_key'] = $config['secret_key'];
        $cosConfig['reigon'] = $config['reigon'];
        $cosConfig['bucket'] = $config['bucket'];
        $cosConfig['signed_url'] = $config['signed_url'];
        $cosConfig['use_https'] = $config['use_https'];
        $cosConfig['domain'] = $config['domain'];
        $cosConfig['cdn'] = $config['cdn'];
        $cosConfig['prefix'] = $config['cos_prefix'] ?? '';
        $cosConfig['guzzle']['timeout'] = $config['cos_guzzle_timeout'] ?? 60;
        $cosConfig['guzzle']['connect_timeout'] = $config['cos_guzzle_connect_timeout'] ?? 60;

        config([
            'filesystems.default' => CosUtility::DISK_KEY,
            'filesystems.disks.cos' => $cosConfig,
        ]);
    }

    public static function getStorage(): FilesystemAdapter
    {
        CosUtility::dynamicsConfig();

        return Storage::disk('cos');
    }

    public static function cleanBucketName(?string $bucket, ?string $appId)
    {
        if (!$bucket || !$appId) {
            return null;
        }

        $cosBucket = $bucket;
        if (str_contains($bucket, $appId)) {
            $appIdSuffix = '-'.$appId;
            $cosBucket = str_replace($appIdSuffix, '', $bucket);
        }

        return $cosBucket;
    }

    public static function cleanHost(?string $domain)
    {
        if (!$domain) {
            return null;
        }

        $host = null;
        if (filter_var($domain, FILTER_VALIDATE_URL)) {
            $urlInfo = parse_url($domain);
            $host = $urlInfo['host'];
        } else {
            $urlInfo = parse_url($domain);

            $host = $urlInfo['path'];
        }

        return $host;
    }
}