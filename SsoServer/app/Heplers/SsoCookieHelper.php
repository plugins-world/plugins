<?php

namespace Plugins\SsoServer\Heplers;

use Illuminate\Support\Facades\Cookie;
use Plugins\SsoServer\Utilities\CookieUtility;

class SsoCookieHelper
{
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

    public static function getToken()
    {
        $token = \request()->get('token');
        if (!$token) {
            $token = SsoCookieHelper::getServerCookieValue();
        }

        return $token;
    }

    public static function getClientAccessUrl()
    {
        $returnUrl = \request('return_url');
        if ($returnUrl) {
            $hyper = str_contains($returnUrl, '?') === false ? '?' : '&';

            $returnUrl = $returnUrl . $hyper . http_build_query([
                SsoCookieHelper::getServerCookieName() => SsoCookieHelper::getServerCookieValue(),
            ]);
        }

        return $returnUrl;
    }
}
