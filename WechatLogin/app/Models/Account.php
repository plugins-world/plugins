<?php

namespace Plugins\WechatLogin\Models;

use Plugins\MarketManager\Models\Traits\FsidTrait;

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

    use FsidTrait;

    public function getFsidKey()
    {
        return 'aid';
    }
}
