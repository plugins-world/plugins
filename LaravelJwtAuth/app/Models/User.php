<?php

namespace Plugins\LaravelJwtAuth\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends \App\Models\User
{
    /**
     * 微信小程序性别合法值
     * 
     * @see https://developers.weixin.qq.com/miniprogram/dev/api/open-api/user-info/UserInfo.html#number-gender
     */
    const GENDER_UNKNOWN = 0;
    const GENDER_MAN = 1;
    const GENDER_FEMAN = 2;
    const GENDER_MAP = [
        User::GENDER_UNKNOWN => '未知',
        User::GENDER_MAN => '男',
        User::GENDER_FEMAN => '女',
    ];

    use SoftDeletes;

    protected $guarded = [];

    public function getGenderDescAttribute()
    {
        if ($this->attributes['gender']) {
            return User::GENDER_MAP[$this->attributes['gender']] ?? "未知类型 {$this->attributes['gender']}";
        }

        return null;
    }

    public function getDetail()
    {
        return $this->toArray();
    }
}
