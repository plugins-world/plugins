<?php

namespace Plugins\EasyMap\Utilities;

use GuzzleHttp\Client;

class AMapUtility
{
    /**
     * platform
     */
    const PLATFORM = 'amap';

    /**
     * 平台申请的key
     *
     * @var string
     */
    private $key;

    /**
     * 接口地址
     *
     * @var string
     */
    private $requestUrl;

    /**
     * @var Client
     */
    private $guzzleHttpClient;

    /**
     * 私有实例对象
     *
     * @var AMapUtility
     */
    private static $instance;

    /**
     * 获取私有实例对象
     *
     * @param string $key
     * @param string $requestUrl
     * @return AMapUtility
     */
    public static function getInstance(string $key, string $requestUrl)
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self($key, $requestUrl);
        }

        return self::$instance;
    }

    /**
     * 私有构造方法
     *
     * AMapUtility constructor.
     * @param string $key
     * @param string $requestUrl
     */
    private function __construct(string $key, string $requestUrl)
    {
        $this->key = $key;
        $this->requestUrl = $requestUrl;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * @param string $requestUrl
     */
    public function setRequestUrl($requestUrl)
    {
        $this->requestUrl = $requestUrl;
    }

    /**
     * 初始化Client
     */
    public function initGuzzleHttpClient()
    {
        if (!$this->guzzleHttpClient instanceof Client) {
            $this->guzzleHttpClient = new Client([
                'base_uri' => $this->requestUrl,
                'timeout' => 1.0
            ]);
        }
    }

    /**
     * @param array $params
     * @param string $action
     * @param string $method
     * @return bool|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $method = 'GET', string $action = '',  array $params = [])
    {
        $this->initGuzzleHttpClient();

        $params['key'] = $this->key;

        $response = $this->guzzleHttpClient->request($method, $action, [
            'query' => $params
        ]);

        if (200 != $response->getStatusCode()) {
            throw new \RuntimeException('请求 高德地图 api 失败');
        }

        $result =  json_decode($response->getBody()->getContents(), true);
        if ($result['status'] == '0') {
            throw new \RuntimeException(sprintf('高德地图 api 返回信息: status: %s, infocode: %s, info: %s, 查看错误码说明: https://lbs.amap.com/api/webservice/guide/tools/info', $result['status'], $result['infocode'], $result['info']), $result['infocode']);
        }

        return $result;
    }
}
