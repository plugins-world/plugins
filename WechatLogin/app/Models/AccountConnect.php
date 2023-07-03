<?php

namespace Plugins\WechatLogin\Models;

use Plugins\FileStorage\Utilities\FileUtility;

class AccountConnect extends Model
{
    protected $casts = [
        'more_json' => 'json',
    ];

    public function getConnectAvatarAttribute()
    {
        $path = $this->attributes['connect_avatar'];
        if (! $path) {
            return null;
        }

        $disk = 'cos';
        FileUtility::initConfig($disk);
        return FileUtility::getStorage($disk)->url($path);
    }
}
