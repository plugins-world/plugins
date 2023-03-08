<?php

namespace Plugins\LaravelQiNiu\Services;

use Illuminate\Support\Facades\Storage;

class QiNiuService
{
    protected $central = false;

    public function withCentral(bool $central = false)
    {
        $this->central = $central;

        return $this;
    }

    public function getQiNiuStorageConfig()
    {
        $keys = [
            'is_central_config',
            'access_key',
            'secret_key',
            'bucket',
            'domain',
        ];

        if ($this->central) {
            return db_config_central($keys);
        }

        return db_config($keys);
    }

    public function isCentralConfig()
    {
        return $this->getQiNiuStorageConfig()['is_central_config'] ?? false;
    }

    public function resetQiNiuConfig()
    {
        $qiniuConfig = config('laravel-qiniu-filesystems.disks.qiniu');

        $userConfig = $this->getQiNiuStorageConfig();

        $qiniuConfig = array_merge($qiniuConfig, $userConfig);

        config([
            'filesystems.default' => $qiniuConfig['driver'],
            'filesystems.disks.qiniu' => $qiniuConfig,
        ]);
    }

    public function getStorage(): ?\Illuminate\Filesystem\FilesystemAdapter
    {
        $this->resetQiNiuConfig();

        return Storage::disk('qiniu');
    }

    /**
     * @return null|\League\Flysystem\FilesystemAdapter|\Overtrue\Flysystem\Qiniu\QiniuAdapter
     */
    public function getAdapter()
    {
        return $this->getStorage()?->getAdapter();
    }

    /**
     * 生成七牛云防盗链，防盗链基于时间戳.
     *
     * @param  string  $url
     * @param  string  $antiLinkKey
     * @param  int  $deadline
     * @param  array  $query
     * @return void
     *
     * @see https://developer.qiniu.com/fusion/kb/1670/timestamp-hotlinking-prevention
     */
    public function getAntiLinkUrl(string $url, string $antiLinkKey, int $deadline, array $query = [])
    {
        $urlInfo = parse_url($url);

        if (empty($urlInfo['path'])) {
            return null;
        }

        $qiniuOriginUrl = sprintf('/%s', ltrim($urlInfo['path'], '/'));

        $accessUrl = $qiniuOriginUrl;
        $accessUrl = implode('/', array_map('rawurlencode', explode('/', $accessUrl)));

        $key = $antiLinkKey;

        $hexDeadline = dechex($deadline);
        $lowerHexDeadline = strtolower($hexDeadline);

        $signString = sprintf('%s%s%s', $key, $accessUrl, $lowerHexDeadline);

        $sign = strtolower(md5($signString));

        $querystring = http_build_query(array_merge($query, [
            'sign' => $sign,
            't' => $lowerHexDeadline,
        ]));

        if (str_contains($url, '?')) {
            $url .= "&{$querystring}";
        } else {
            $url .= "?{$querystring}";
        }

        return $url;
    }

    /**
     * 七牛云转码、生成视频截图.
     *
     * @param  \Qiniu\Auth  $auth
     * @param  string  $transParams
     * @param  string  $bucket
     * @param  string  $dir
     * @param  string  $key
     * @param  string  $filename
     * @param  string  $notifyUrl
     * @return array
     *
     * @see https://developer.qiniu.com/dora/api/persistent-data-processing-pfop#4
     */
    public function executeTranscoding(\Qiniu\Auth $auth, ?string $transParams, string $bucket, string $dir, string $key, string $filename, string $notifyUrl): array
    {
        if (empty($transParams)) {
            return null;
        }

        $key = ltrim($key, '/');

        $pfop = new \Qiniu\Processing\PersistentFop($auth);

        // 截图文件存放位置
        $filepath = sprintf('%s/%s', rtrim($dir, '/'), ltrim($filename, '/'));

        $saveAs = "$bucket:$filepath";

        $fops = $transParams . '|saveas/' . \Qiniu\base64_urlSafeEncode($saveAs);
        $pipeline = 'default.sys';
        $force = false;

        [$id,] = $pfop->execute($bucket, $key, $fops, $pipeline, $notifyUrl, $force);

        return [
            'id' => $id,
            'path' => $filepath,
        ];
    }
}
