<?php

namespace Plugins\SsoServer\Heplers;

use MouYong\LaravelConfig\Models\Config;

class SsoHelper
{
    public static function getLoginUrl($redirect = false)
    {
        $url = Config::getValueByKey('sso_login_url');
        if (!$url) {
            $url = route('sso-server.login');
        }

        if (!$redirect) {
            return $url;
        }

        return static::getRedirectUrl($url);
    }

    public static function getLogoutUrl($redirect = false)
    {
        $url = Config::getValueByKey('sso_logout_url');

        if (!$url) {
            $url = route('sso-server.logout');
        }

        if (!$redirect) {
            return $url;
        }

        return static::getRedirectUrl($url);
    }

    public static function getRegisterUrl($redirect = false)
    {
        $url = Config::getValueByKey('sso_register_url');

        if (!$url) {
            $url = route('sso-server.register');
        }

        if (!$redirect) {
            return $url;
        }

        return static::getRedirectUrl($url);
    }

    public static function getIndexUrl($redirect = false)
    {
        $url = Config::getValueByKey('sso_index_url');

        if (!$url) {
            $url = route('sso-server.index');
        }

        if (!$redirect) {
            return $url;
        }

        return static::getRedirectUrl($url);
    }

    public static function getServiceUrl($redirect = false)
    {
        $url = Config::getValueByKey('sso_service_url');

        if (!$url) {
            $url = route('sso-server.service');
        }

        if (!$redirect) {
            return $url;
        }

        return static::getRedirectUrl($url);
    }

    public static function getValidateUrl($redirect = false)
    {
        $url = Config::getValueByKey('sso_validate_url');

        if (!$url) {
            $url = route('sso-server.api.validate');
        }

        if (!$redirect) {
            return $url;
        }

        return static::getRedirectUrl($url);
    }

    public static function getUserInfoUrl($redirect = false)
    {
        $url = Config::getValueByKey('sso_get_user_info_api');

        if (!$url) {
            $url = route('sso-server.api.userinfo');
        }

        if (!$redirect) {
            return $url;
        }

        return static::getRedirectUrl($url);
    }

    public static function getPublicKeyUrl()
    {
        $url = Config::getValueByKey('sso_get_public_key_api');

        if (!$url) {
            return $url;
        }

        return route('sso-server.api.public_key');
    }

    public static function getRedirectUrl($targetUrl)
    {
        $returnUrl = \request('return_url');

        $params = array_filter([
            'return_url' => $returnUrl,
        ]);

        $queryString = http_build_query($params);

        $hyper = '?';
        if (str_contains($targetUrl, '?')) {
            $hyper = '&';
        }

        if ($queryString) {
            $queryString = $hyper.$queryString;
        }

        return $targetUrl.$queryString;
    }

    public static function getUrlInfo()
    {
        return [
            'sso_login_url' => static::getLoginUrl(),
            'sso_logout_url' => static::getLogoutUrl(),
            'sso_register_url' => static::getRegisterUrl(),
            'sso_service_url' => static::getServiceUrl(),
            'sso_validate_url' => static::getValidateUrl(),
            'sso_get_user_info_api' => static::getUserInfoUrl(),
            'sso_get_public_key_api' => static::getPublicKeyUrl(),
        ];
    }
}
