<?php

namespace Plugins\WechatLogin\Models;

use App\Models\User;

class AccountUser extends Model
{
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
