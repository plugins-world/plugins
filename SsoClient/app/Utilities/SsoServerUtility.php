<?php

namespace Plugins\SsoClient\Utilities;

class SsoServerUtility
{
    public static function serverLoginCheck(string $serverValue)
    {
        $url = ConfigUtility::getServerConfig('sso_get_user_info_api', '');

        $response = curl('post', $url, [
            'token' => $serverValue,
        ], [
        ]);

        $data = json_decode($response['data']['response'], true);
        if (empty($data)) {
            return false;
        }

        if ($data['err_code'] !== 200) {
            return false;
        }

        return true;
    }

    public static function serverGetUserInfo(string $serverValue)
    {
        $url = ConfigUtility::getServerConfig('sso_get_user_info_api', '');

        $response = curl('post', $url, [
            'token' => $serverValue,
        ], [
        ]);

        $result = json_decode($response['data']['response'], true);
        if (empty($result)) {
            return false;
        }

        if ($result['err_code'] !== 200) {
            return false;
        }

        return $result['data'] ?? [];
    }
}
