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
            'region',
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
            $config = central(fn() => CosUtility::getConfig());
        }

        $cosConfig['driver'] = CosUtility::DISK_KEY;
        $cosConfig['app_id'] = $config['app_id'];
        $cosConfig['secret_id'] = $config['secret_id'];
        $cosConfig['secret_key'] = $config['secret_key'];
        $cosConfig['region'] = $config['region'];
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
            $appIdSuffix = '-' . $appId;
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

    public static function getKeyAndCredentials(string $savePath)
    {
        $cosConfig = config('filesystems.disks.cos', []);
        $secretId = $cosConfig['secret_id'];
        $secretKey = $cosConfig['secret_key'];
        $bucketName = $cosConfig['bucket'];
        $bucketAppId = $cosConfig['app_id'];
        $region = $cosConfig['region'];
        $bucket = sprintf('%s-%s', $bucketName, $bucketAppId);

        config([
            'federation-token' => [
                'default' => [
                    'secret_id' => $secretId,
                    'secret_key' => $secretKey,
                    'region' => $region,
                    'variables' => [
                        'bucket' => $bucketName,
                        'appid' => $bucketAppId,
                    ],
                ],
                'strategies' => [
                    'default' => [
                        "statements" => [
                            [
                                "action" => [
                                    "cos:PutObject",
                                    "cos:GetObject",
                                ],
                                "resource" => [
                                    "qcs::cos:$region:uid/<appid>:<bucket>-<appid>/$savePath",
                                ],
                            ]
                        ],
                    ],
                ],
            ],
        ]);

        $token = \Overtrue\LaravelQcloudFederationToken\FederationToken::createToken();
        // $strategy = \Overtrue\LaravelQcloudFederationToken\FederationToken::strategy();
        $data = $token->toArray();

        return array_merge($data, [
            'startTime' => time(),
            'expiredTime' => $data['expired_at'],
            'bucket' => $bucket,
            'region' => $region,
            'key' => $savePath,
            // 'resources' => $strategy->getStatements()[0]['resource'] ?? [],
        ]);
    }

    public static function getKeyAndCredentialsSuccessfulOnPublic(string $savePath)
    {
        $cosConfig = config('filesystems.disks.cos', []);

        // 配置腾讯云COS信息
        $secretId = $cosConfig['secret_id'];
        $secretKey = $cosConfig['secret_key'];
        $bucket = sprintf('%s-%s', $cosConfig['bucket'], $cosConfig['app_id']); // 换成你的 bucket
        $region = $cosConfig['region']; // 换成 bucket 所在园区
        $expiredTime = 1800; // 策略过期时间（秒）

        // 生成Policy
        $expiration = date('Y-m-d\TH:i:s\Z', time() + $expiredTime);
        $policy = [
            'expiration' => $expiration,
            'conditions' => [
                ['bucket' => $bucket],
                // ['starts-with', '$key', $savePath], // 限制上传路径
                ['content-length-range', 0, 10485760] // 限制文件大小10MB
            ]
        ];

        // 将Policy转为JSON并Base64编码
        $policyJSON = json_encode($policy);
        $policyBase64 = base64_encode($policyJSON);

        // 生成签名
        $signature = base64_encode(hash_hmac('sha1', $policyBase64, $secretKey, true));

        // 构造返回数据
        $response = [
            'policy' => $policyBase64,
            'signature' => $signature,
            'secretId' => $secretId,
            'bucket' => $bucket,
            'region' => $region,
            'uploadUrl' => "https://{$bucket}.cos.{$region}.myqcloud.com",
            'cosHost' => "https://{$bucket}.cos.{$region}.myqcloud.com",
        ];

        $response['key'] = $savePath;



        /** @var \Overtrue\Flysystem\Cos\CosAdapter */
        // $adapter = CosUtility::getStorage()->getAdapter();
        // $objectUrl = $adapter->getObjectClient()->getObjectUrl($savePath);
        // $objectUrl = sprintf("https://%s.cos.%s.myqcloud.com/%s", $bucket, $region, $savePath);
        // $signature = new \Overtrue\CosClient\Signature($cosConfig['secret_id'], $cosConfig['secret_key']);
        // $request = new \GuzzleHttp\Psr7\Request('POST', $objectUrl);
        // $signString = $signature->createAuthorizationHeader($request);
        // parse_str($signString, $signInfo);
        // dd($signInfo, $signString);
        // $response = array_merge($response, $signInfo);



        // header('Content-Type: application/json');
        return $response;
    }

    /**
     * 前端直传, 获取单一文件上传权限的临时密钥
     *
     * 需要再 bucket 的安全设置，允许 cors 跨域
     *
     * @param string $savePath
     * @return array
     *
     * @see https://cloud.tencent.com/document/product/436/9067#.E6.96.B9.E6.A1.88.E4.BC.98.E5.8A.BF
     */
    public static function getKeyAndCredentialsFail(string $savePath)
    {
        $cosConfig = config('filesystems.disks.cos', []);
        if (empty($cosConfig)) {
            throw new \RuntimeException('请完善 cos 配置信息');
        }

        $cosKey = $savePath;
        $bucket = sprintf('%s-%s', $cosConfig['bucket'], $cosConfig['app_id']); // 换成你的 bucket
        $region = $cosConfig['region']; // 换成 bucket 所在园区

        /** @var \Overtrue\Flysystem\Cos\CosAdapter */
        $adapter = CosUtility::getStorage()->getAdapter();
        $objectUrl = $adapter->getObjectClient()->getObjectUrl($savePath);
        $objectUrl = sprintf("https://%s.cos.%s.myqcloud.com/%s", $bucket, $region, $savePath);


        $signature = new \Overtrue\CosClient\Signature($cosConfig['secret_id'], $cosConfig['secret_key']);
        $request = new \GuzzleHttp\Psr7\Request('POST', $objectUrl);
        $signString = $signature->createAuthorizationHeader($request);
        parse_str($signString, $signInfo);


        // 业务自行实现 用户登录态校验，比如对 token 校验
        // $canUpload = checkUserRole($userToken);
        // if (!$canUpload) {
        //   return '当前用户没有上传权限';
        // }

        // 上传文件可控制类型、大小，按需开启
        $permission = array(
            'limitExt' => false, // 限制上传文件后缀
            'extWhiteList' => ['jpg', 'jpeg', 'png', 'gif', 'bmp'], // 限制的上传后缀
            'limitContentType' => false, // 限制上传 contentType
            'limitContentLength' => false, // 限制上传文件大小
        );
        $condition = array();

        // 客户端传进原始文件名，这里根据文件后缀生成随机 Key
        $ext = pathinfo($savePath, PATHINFO_EXTENSION);

        // 1. 限制上传文件后缀
        if ($permission['limitExt']) {
            if ($ext === '' || array_key_exists($ext, $permission['extWhiteList'])) {
                throw new \RuntimeException('非法文件，禁止上传');
            }
        }

        // 2. 限制上传文件 content-type
        if ($permission['limitContentType']) {
            // 只允许上传 content-type 为图片类型
            $condition['string_like_if_exist'] = array('cos:content-type' => 'image/*');
        }

        // 3. 限制上传文件大小
        if ($permission['limitContentLength']) {
            // 上传大小限制不能超过 5MB(只对简单上传生效)
            $condition['numeric_less_than_equal'] = array('cos:content-length' => 5 * 1024 * 1024);
        }

        $config = array(
            'url' => 'https://sts.tencentcloudapi.com/', // url和domain保持一致
            'domain' => 'sts.tencentcloudapi.com', // 域名，非必须，默认为 sts.tencentcloudapi.com
            'proxy' => '',
            'secretId' => $cosConfig['secret_id'], // 固定密钥,若为明文密钥，请直接以'xxx'形式填入，不要填写到getenv()函数中
            'secretKey' => $cosConfig['secret_key'], // 固定密钥,若为明文密钥，请直接以'xxx'形式填入，不要填写到getenv()函数中
            'bucket' => $bucket, // 换成你的 bucket
            'region' => $region, // 换成 bucket 所在园区
            'durationSeconds' => 1800, // 密钥有效期
            'allowPrefix' => array($cosKey), // 只分配当前 key 的路径权限
            // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
            'allowActions' => array(
                // // 这里可以从临时密钥的权限上控制前端允许的操作
                //'name/cos:*', // 这样写可以包含下面所有权限

                // // 列出所有允许的操作
                // // ACL 读写
                // 'name/cos:GetBucketACL',
                // 'name/cos:PutBucketACL',
                // 'name/cos:GetObjectACL',
                // 'name/cos:PutObjectACL',
                // // 简单 Bucket 操作
                // 'name/cos:PutBucket',
                // 'name/cos:HeadBucket',
                // 'name/cos:GetBucket',
                // 'name/cos:DeleteBucket',
                // 'name/cos:GetBucketLocation',
                // // Versioning
                // 'name/cos:PutBucketVersioning',
                // 'name/cos:GetBucketVersioning',
                // // CORS
                // 'name/cos:PutBucketCORS',
                // 'name/cos:GetBucketCORS',
                // 'name/cos:DeleteBucketCORS',
                // // Lifecycle
                // 'name/cos:PutBucketLifecycle',
                // 'name/cos:GetBucketLifecycle',
                // 'name/cos:DeleteBucketLifecycle',
                // // Replication
                // 'name/cos:PutBucketReplication',
                // 'name/cos:GetBucketReplication',
                // 'name/cos:DeleteBucketReplication',
                // // 删除文件
                // 'name/cos:DeleteMultipleObject',
                // 'name/cos:DeleteObject',
                // 简单文件操作
                'name/cos:PutObject',
                'name/cos:PostObject',
                'name/cos:AppendObject',
                'name/cos:GetObject',
                'name/cos:HeadObject',
                'name/cos:OptionsObject',
                'name/cos:PutObjectCopy',
                'name/cos:PostObjectRestore',
                // 分片上传操作
                'name/cos:InitiateMultipartUpload',
                'name/cos:ListMultipartUploads',
                'name/cos:ListParts',
                'name/cos:UploadPart',
                'name/cos:CompleteMultipartUpload',
                'name/cos:AbortMultipartUpload',
            ),
        );

        if (!empty($condition)) {
            $config['condition'] = $condition;
        }


        $startTime = time();
        $expiredTime = 1800; // 策略过期时间（秒）
        $expiration = date('Y-m-d\TH:i:s\Z', $startTime + $expiredTime);

        $policy = [
            'expiration' => $expiration,
            'conditions' => [
                ['bucket' => $bucket],
                ['q-sign-algorithm' => $signInfo['q-sign-algorithm']],
                ['q-ak' => $signInfo['q-ak']],
                // ['starts-with', '$key', dirname($savePath)], // 限制上传路径
                // ['starts-with', '$key', $savePath], // 限制上传路径
                // ['content-length-range', 0, 10485760], // 限制文件大小10MB
                // ['eq', '$q-sign-time', $signInfo['q-sign-time']], // 这个格式
                ['q-sign-time' => $signInfo['q-sign-time']], // 这样也可以
            ]
        ];

        $sts = new \Plugins\FileStorage\Utilities\StsUtility();
        $tempKeys = $sts->getTempKeys($config);


        // 将Policy转为JSON并Base64编码
        $policyJSON = json_encode($policy);
        $policyBase64 = base64_encode($policyJSON);

        $secretKey = $cosConfig['secret_key'];
        $signature = base64_encode(hash_hmac('sha1', $policyBase64, $secretKey, true));

        $cosHost = sprintf("https://%s.cos.%s.myqcloud.com", $bucket, $region);
        $extra = [
            'cosHost' => $cosHost,
            'startTime' => $startTime,
            'bucket' => $bucket,
            'region' => $region,
            'key' => $cosKey,
            'policy' => $policyBase64,
            'secretId' => $cosConfig['secret_id'],
            'signature' => $signature,
        ];

        $resTemp = array_merge(
            $tempKeys,
            $extra,
            $signInfo
        );

        // \info('cos temp sts', json_encode($resTemp, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));

        return $resTemp;
    }
}
