<?php

namespace Plugins\ClsLogger\Utilties;

use TencentCloud\Cls\Models\Request\PutLogsRequest;
use TencentCloud\Cls\Models\LogItem;
use TencentCloud\Cls\Client;
use TencentCloud\Cls\TencentCloudLogException;
use Plugins\LaravelConfig\Helpers\ConfigHelper;
use Plugins\LaravelConfig\Utilities\ConfigUtility;

class ClsLogUtiltity
{
    public static function getConfig($options = [])
    {
        $currentConfig = ConfigHelper::fresnsConfigByItemKeys([
            'endpoint',
            'access_key_id',
            'access_key',
            'topic_id',
            'token',
        ], 'cls_logger');

        $config = array_merge([
            'endpoint' => $currentConfig['endpoint'],
            'accessKeyId' => $currentConfig['access_key_id'],
            'accessKey' => $currentConfig['access_key'],
            'topicId' => $currentConfig['topic_id'],
            'token' => $currentConfig['token'],
        ], $options);

        return $config;
    }

    public static function getClient($config)
    {
        $endpoint = $config['endpoint'];
        $accessKeyId = $config['accessKeyId'];
        $accessKey = $config['accessKey'];
        $token = $config['token'];

        $client = new Client($endpoint, $accessKeyId, $accessKey, $token);

        return $client;
    }

    public static function putLogs($config, $data)
    {
        $topicId = $config['topicId'];
        $client = ClsLogUtiltity::getClient($config);

        $contents = $data;
        $ms = (int) floor(microtime(true) * 1000);

        $logItem = new LogItem();
        $logItem->setTime($ms);
        $logItem->setContents($contents);
        $logItems = array($logItem);
        $request = new PutLogsRequest($topicId, null, $logItems);

        try {
            $response = $client->putLogs($request);
            \Log::channel('stderr')->info("Log RequestId: {$response->getRequestId()}");
            return true;
        } catch (TencentCloudLogException $ex) {
            \Log::channel('stderr')->error($exception->getMessage());
            return false;
        } catch (Exception $ex) {
            \Log::channel('stderr')->error($exception->getMessage());
            return false;
        }
    }

    public static function logToCls($level, $message, array $context = []): void
    {
        try {
            $message = is_string($message) ? $message : (string) $message;
        } catch (\Throwable $exception) {
            \Log::channel('stderr')->error($exception->getMessage());
            throw new InvalidArgumentException("args type invalid");
        }

        if (!empty($context)) {
            $message = sprintf("%s %s", $message, json_encode($context, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        }

        $config = ClsLogUtiltity::getConfig();

        $data = [];
        $data['app_name'] = config('app.name');
        $data['environment'] = app()->environment();
        $data['level'] = $level;
        $data['message'] = $message;

        try {
            ClsLogUtiltity::putLogs($config, $data);
        } catch (\Throwable $exception) {
            \Log::channel('stderr')->error($exception->getMessage());
        }
    }
}
