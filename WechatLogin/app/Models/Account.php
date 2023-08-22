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
        $userModelClass = \App\Models\User::class;
        if (class_exists(User::class)) {
            $userModelClass = User::class;
        }

        return $this->hasManyThrough($userModelClass, AccountUser::class, 'account_id', 'id', 'id', 'user_id');
    }

    public function firstUser()
    {
        $userModelClass = \App\Models\User::class;
        if (class_exists(User::class)) {
            $userModelClass = User::class;
        }

        return $this->hasManyThrough($userModelClass, AccountUser::class, 'account_id', 'id', 'id', 'user_id')->orderBy('id');
    }

    public function lastUser()
    {
        $userModelClass = \App\Models\User::class;
        if (class_exists(User::class)) {
            $userModelClass = User::class;
        }

        return $this->hasManyThrough($userModelClass, AccountUser::class, 'account_id', 'id', 'id', 'user_id')->orderByDesc('id');
    }
}
