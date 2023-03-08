<?php

namespace Plugins\SsoClient\Heplers;

use Illuminate\Support\Facades\Cookie;
use Plugins\SsoClient\Utilities\CookieUtility;
use Plugins\SsoClient\Utilities\SsoServerUtility;
use Plugins\SsoClient\Heplers\UserHelper;

class SsoCookieHelper
{
    public static function getClientCookieName()
    {
        $cookieName = CookieUtility::getCookieName('client');

        return $cookieName;
    }

    public static function getClientCookieValue()
    {
        $cookieName = CookieUtility::getCookieName('client');
        $cookieValue = Cookie::get($cookieName);

        return $cookieValue;
    }

    public static function getServerCookieName()
    {
        $cookieName = CookieUtility::getCookieName('server');

        return $cookieName;
    }

    public static function getServerCookieValue()
    {
        $cookieName = CookieUtility::getCookieName('server');
        $cookieValue = Cookie::get($cookieName);

        return $cookieValue;
    }

    public static function getServerLoginValue()
    {
        $serverLoginValue = \request(SsoCookieHelper::getServerCookieName());

        return $serverLoginValue;
    }

    public static function getServerToken()
    {
        $serverToken = SsoCookieHelper::getServerLoginValue() ?? SsoCookieHelper::getServerCookieValue();

        return $serverToken;
    }

    public static function serverLoginCheck(string $serverValue)
    {
        return SsoServerUtility::serverLoginCheck($serverValue);
    }

    public static function loginUser(string $serverValue)
    {
        $serverCookieName = SsoCookieHelper::getServerCookieName();
        $serverValue = SsoCookieHelper::getServerToken();

        $clientCookieName = SsoCookieHelper::getClientCookieName();
        $clientCookieValue = SsoCookieHelper::getClientCookieValue();
        if (!$clientCookieValue) {
            $userInfo = SsoServerUtility::serverGetUserInfo($serverValue);

            // 发布用户信息更新消息
            UserHelper::publicUpdateUserInfo($userInfo);

            $clientCookieValue = json_encode($userInfo, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        }

        \Illuminate\Support\Facades\Cookie::queue($serverCookieName, $serverValue);
        \Illuminate\Support\Facades\Cookie::queue($clientCookieName, $clientCookieValue);

        return true;
    }

    public static function logoutUser()
    {
        $serverCookieName = SsoCookieHelper::getServerCookieName();
        $clientCookieName = SsoCookieHelper::getClientCookieName();

        \Illuminate\Support\Facades\Cookie::queue($serverCookieName, null);
        \Illuminate\Support\Facades\Cookie::queue($clientCookieName, null);
    }
}
