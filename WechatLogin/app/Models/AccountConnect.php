<?php

namespace Plugins\WechatLogin\Models;

class AccountConnect extends Model
{
    protected $casts = [
        'more_json' => 'json',
    ];

    public function getConnectAvatarAttribute()
    {
        $filepath = $this->attributes['connect_avatar'];
        if (!$filepath) {
            return null;
        }

        $resp = \FresnsCmdWord::plugin('FileStorage')->getFileUrl([
            'fileId' => null,
            'filepath' => $filepath,
        ]);

        return $resp->getData('file_url');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
