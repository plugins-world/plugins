<?php

namespace Plugins\SsoServer\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MouYong\LaravelConfig\Models\Config;
use Plugins\SsoServer\Heplers\SsoHelper;
use ZhenMu\Support\Traits\ResponseTrait;
use Plugins\SsoServer\Heplers\SsoCookieHelper;
use Plugins\SsoServer\Heplers\UserHelper;

class ApiController extends Controller
{
    use ResponseTrait;

    public function getConfig()
    {
        $dbConfig = Config::getValueByKeys([
            'rsa_public_key',
            'sso_cookie_prefix',
        ]);
        if (!($dbConfig['rsa_public_key'] ?? null)) {
            return $this->fail('RSA public_key 不存在');
        }

        $serverUrls = SsoHelper::getUrlInfo();

        $config = [];
        
        $config = array_merge($dbConfig, $serverUrls, $config);

        return $this->success($config);
    }

    public function getUserInfo()
    {
        $token = SsoCookieHelper::getToken();
        if (!$token) {
            return $this->fail('token 无效');
        }

        $ssoUser = UserHelper::getUserRepository()->findUserByToken($token);
        if (!$ssoUser) {
            return $this->fail('用户不存在');
        }

        return $this->success([
            'uid' => $ssoUser->uid,
            'username' => $ssoUser->username,
            'nickname' => $ssoUser->nickname,
            'avatar' => $ssoUser->avatar,
        ]);
    }

    public function ssoValidate()
    {
        $token = SsoCookieHelper::getToken();
        if (!$token) {
            return $this->fail('token 无效');
        }

        $ssoUser = UserHelper::getUserRepository()->findUserByToken($token);
        if (!$ssoUser) {
            return $this->fail('用户不存在');
        }

        $ssoUserSite = UserHelper::getUserSiteRepository()->findUserSiteTokenByToken($ssoUser->currentAccessToken()->plainTextToken);
        if (!$ssoUserSite) {
            return $this->fail('用户未登录');
        }

        if ($ssoUserSite->expire_time->isPast()) {
            \info('token 已过期');
            return false;
        }

        return $this->success();
    }
}
