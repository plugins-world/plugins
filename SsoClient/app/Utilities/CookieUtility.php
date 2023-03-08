<?php

namespace Plugins\SsoClient\Utilities;

use MouYong\LaravelConfig\Models\Config;

class CookieUtility
{
    /**
     * get cookie name
     *
     * @param  string $type server, client
     * @return string
     */
    public static function getCookieName($type)
    {
        $cookiePrefix = static::getCookiePrefix();
        $cookiePrefix = trim($cookiePrefix, ':');
        $type = trim($type, ':');

        $cookieName = $cookiePrefix . ':sso:' . $type;
        $cookieName = trim($cookieName, ':');

        return $cookieName;
    }

    /**
     * get cookie prefix
     *
     * @return string
     */
    public static function getCookiePrefix()
    {
        try {
            $value = ConfigUtility::getServerConfig('sso_cookie_prefix', '');

            if (!$value) {
                return '';
            }

            return $value;
        } catch (\Throwable $e) {
            \info('获取 sso_cookie_prefix 失败:' . $e->getMessage());
            return '';
        }
    }
}
