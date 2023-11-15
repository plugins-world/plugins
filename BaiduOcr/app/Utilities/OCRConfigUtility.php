<?php

namespace Plugins\BaiduOcr\Utilities;

use GuzzleHttp\Client;

class OCRConfigUtility
{
    /**
     * 百度API Key
     *
     * @var string
     */
    private $apiKey;

    /**
     * 百度 Secret Key
     *
     * @var string
     */
    private $secretKey;

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
     * @var
     */
    private static $instance;

    /**
     * 获取私有实例对象
     *
     * @param string $apiKey
     * @param string $secretKey
     * @param string $requestUrl
     * @return OCRConfigUtility
     */
    public static function getInstance(string $apiKey, string $secretKey, string $requestUrl)
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self($apiKey, $secretKey, $requestUrl);
        }

        return self::$instance;
    }

    /**
     * 私有构造方法
     *
     * OCRConfigUtility constructor.
     * @param string $apiKey
     * @param string $secretKey
     * @param string $requestUrl
     */
    private function __construct(string $apiKey, string $secretKey, string $requestUrl)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
        $this->requestUrl = $requestUrl;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
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

    public function request(string $method = 'GET', string $action = '', array $params = [])
    {
        $this->initGuzzleHttpClient();

        $response = $this->guzzleHttpClient->request($method, $action, $params);

        if (200 != $response->getStatusCode()) {
            throw new \RuntimeException('请求 百度OCR api 失败');
        }

        $result =  json_decode($response->getBody()->getContents(), true);
        if (isset($result['error']) || isset($result['error_code'])) {

            $errorCode = $result['error'] ?? $result['error_code'];
            $errorMsg = $result['error_description'] ?? $result['error_msg'];

            throw new \RuntimeException(sprintf('百度OCR api: 返回信息: error_code: %s, error_msg: %s, 查看错误码说明: https://ai.baidu.com/ai-doc/REFERENCE/nkrq73xox', $errorCode, $errorMsg));
        }

        return $result;
    }
}
