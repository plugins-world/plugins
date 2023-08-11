<?php

namespace Plugins\WechatLogin\Models;

class Account extends Model
{
    const TYPE_1 = 1;
    const TYPE_2 = 2;
    const TYPE_3 = 3;
    const TYPE_MAP = [
        Account::TYPE_1 => '超级管理员',
        Account::TYPE_2 => '普通管理员',
        Account::TYPE_3 => '普通用户',
    ];

    public function getFsidKey()
    {
        return 'aid';
    }

    public function accountUser()
    {
        return $this->hasMany(AccountUser::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, AccountUser::class);
    }
}
