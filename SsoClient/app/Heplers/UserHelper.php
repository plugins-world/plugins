<?php

namespace Plugins\SsoClient\Heplers;

use MouYong\LaravelConfig\Models\Config;

class UserHelper
{
    public static function publicUpdateUserInfo(array $userInfo)
    {
        event('sso-client:user.login', [
            $userInfo,
        ]);

        $unikey = Config::getValueByKey('sso_update_userinfo_service');
        if (!$unikey) {
            return false;
        }

        $wordBody = [];
        $wordBody['uid'] = $userInfo['uid'];
        $wordBody['username'] = $userInfo['username'];
        $wordBody['nickname'] = $userInfo['nickname'];
        $wordBody['avatar'] = $userInfo['avatar'];

        $fresnsResp = \FresnsCmdWord::plugin($unikey)->updateUserInfo($wordBody);
        if ($fresnsResp->isErrorResponse()) {
            throw new \RuntimeException($fresnsResp->getMessage(), $fresnsResp->getCode());
        }

        return $fresnsResp;
    }
}