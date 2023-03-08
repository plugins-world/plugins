<?php

namespace Plugins\SsoServer\Utilities;

class StrUtility
{
    public static function generateSsoId()
    {
        $ssoId = uniqid();

        return $ssoId;
    }
}