<?php

namespace Plugins\SsoClient\Utilities;

use MouYong\LaravelConfig\Models\Config;

class ConfigUtility
{
    public static function getServerConfig(string $key, $default = null)
    {
        $dataFilePath = plugin_path('SsoClient/sso_config.dat');

        // 判断文件加载时间是否超过 2 小时
        $isValid = false;
        if (file_exists($dataFilePath)) {
            $isValid = time() - filemtime($dataFilePath) < 7200 ? true : false;
        }

        $config = [];
        if ($isValid) {
            $contents = file_get_contents($dataFilePath);
            $config = json_decode($contents, true);
        }

        if (empty($config)) {
            $serverApiHost = Config::getValueByKey('sso_server_host');

            if ($serverApiHost) {
                $url = StrUtility::getSsoServerUrl("/api/sso-server/get-config");

                $response = curl('get', $url, [
                ], [
                    'Content-Type' => 'application/json',
                ]);

                $result = json_decode($response['data']['response'], true);

                $data = $result['data'] ?? [];

                try {
                    file_put_contents($dataFilePath, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
                } catch (\Throwable $e) {
                    unlink($dataFilePath);
                    \info('save data error:' . $e->getMessage());
                }

                $config = $data;
            }
        }

        return $config[$key] ?? $default;
    }
}
