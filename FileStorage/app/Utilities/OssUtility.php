<?php

namespace Plugins\FileStorage\Utilities;

use Illuminate\Support\Facades\Storage;
use Plugins\LaravelConfig\Models\Config;
use Illuminate\Filesystem\FilesystemAdapter;

class OssUtility
{
    const DISK_KEY = 'oss';
    
    public static function getConfig(): array
    {
        $itemKeys = [
            'is_use_center_config',
            'oss_root',
            'oss_access_key',
            'oss_secret_key',
            'oss_endpoint',
            'oss_bucket',
        ];

        return Config::getValueByKeys($itemKeys);
    }

    public static function dynamicsConfig()
    {
        $config = OssUtility::getConfig();
        if (is_tenant_mode() && $config['is_use_center_config']) {
            $config = central(fn () => OssUtility::getConfig());
        }

        $ossConfig['driver'] = OssUtility::DISK_KEY;
        $ossConfig['root'] = $config['oss_root'];
        $ossConfig['access_key'] = $config['oss_access_key'];
        $ossConfig['secret_key'] = $config['oss_secret_key'];
        $ossConfig['endpoint'] = $config['oss_endpoint'];
        $ossConfig['bucket'] = $config['oss_bucket'];
        $ossConfig['isCName'] = false;

        config([
            'filesystems.default' => OssUtility::DISK_KEY,
            'filesystems.disks.oss' => $ossConfig,
        ]);
    }

    public static function getStorage(): FilesystemAdapter
    {
        OssUtility::dynamicsConfig();

        return Storage::disk('oss');
    }

    public static function cleanBucketName(?string $bucket, ?string $accessKey)
    {
        if (!$bucket || !$accessKey) {
            return null;
        }

        $ossBucket = $bucket;
        if (str_contains($bucket, $accessKey)) {
            $accessKeyIdSuffix = '-'.$accessKey;
            $ossBucket = str_replace($accessKeyIdSuffix, '', $bucket);
        }

        return $ossBucket;
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
