<?php

namespace Plugins\SsoServer\Heplers;

use Illuminate\Support\Facades\Hash;
use Plugins\SsoServer\Models\SsoUser;
use Illuminate\Support\Facades\Cookie;
use Plugins\SsoServer\Repositories\SsoUserRepository;
use Plugins\SsoServer\Repositories\SsoUserSiteRepository;

class UserHelper
{
    public static function getUserRepository(): SsoUserRepository
    {
        return new SsoUserRepository();
    }

    public static function getUserSiteRepository(): SsoUserSiteRepository
    {
        return new SsoUserSiteRepository();
        return new SsoUserSiteRepository();
    }

    public static function userRegister(array $params)
    {
        if ($params['password'] !== $params['password_confirmation']) {
            throw new \RuntimeException('两次输入的密码不一致');
        }

        unset($params['password_confirmation']);

        $data = [];
        $data['username'] = $params['username'];
        $data['password'] = Hash::make($params['password']);

        $user = UserHelper::getUserRepository()->addUser($data);

        return $user;
    }

    protected static function userPasswordCompare($userSavedPassword, $userInputPassword)
    {
        return Hash::check($userInputPassword, $userSavedPassword);
    }

    protected static function userPasswordIsValid(SsoUser $user, $userInputPassword)
    {
        return UserHelper::userPasswordCompare($user->password, $userInputPassword);
    }

    public static function ssoServerLoginCheck()
    {
        // 从 request、cookie 获取用户的 uid 信息
        // 判断用户 uid 是否是 sso 服务中的用户
        // 是，登录成功
        // 否，未登录
        $token = SsoCookieHelper::getToken();

        if (!$token) {
            return false;
        }

        $ssoUser = UserHelper::getUserRepository()->findUserByToken($token);
        if (!$ssoUser) {
            return false;
        }

        // 检测用户是否在 sso server 登录过
        $ssoUserSite = UserHelper::getUserSiteRepository()->findUserSiteTokenByToken($ssoUser->currentAccessToken()->plainTextToken);
        if (!$ssoUserSite) {
            return false;
        }

        if ($ssoUserSite->expire_time->isPast()) {
            \info('token 已过期');
            return false;
        }

        return true;
    }

    public static function userLogin($username, $password)
    {
        // 确认用户输入的用户名
        // 确认用户的密码是否正确
        // 正确，在 cookie 写入用户的 uid，代表已登录
        $user = UserHelper::getUserRepository()->findUserByUsername($username);
        if (!$user) {
            throw new \RuntimeException('用户不存在');
        }

        if (!UserHelper::userPasswordIsValid($user, $password)) {
            throw new \RuntimeException('密码不正确');
        }

        // 设置过期时间
        // token expiration
        config([
            'sanctum.expiration' => 60, # minutes
        ]);

        $ssoUserSite = UserHelper::getUserSiteRepository()->createSsoUserSiteForUser($user, [
            'site_domain' => $_SERVER['HTTP_HOST'],
        ]);

        $cookieName = SsoCookieHelper::getServerCookieName();
        Cookie::queue($cookieName, $ssoUserSite->token);

        return $user;
    }

    public static function userLogout()
    {
        // 清理用户的 cookie
        $cookieName = SsoCookieHelper::getServerCookieName();
        $cookieValue = SsoCookieHelper::getServerCookieValue();

        $user = UserHelper::getUserRepository()->findUserByToken($cookieValue);

        UserHelper::getUserSiteRepository()->remoteSsoUserSiteWithToken($user, $_SERVER['HTTP_HOST']);

        Cookie::queue($cookieName, null);

        return true;
    }
}
