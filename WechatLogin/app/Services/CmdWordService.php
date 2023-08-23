<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\WechatLogin\Services;

use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;
use Plugins\WechatLogin\Utilities\AccountUtility;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function getAccountOfUser(array $wordBody)
    {
        $user = $wordBody['user'];

        $account = AccountUtility::getAccountOfUser($user);

        return $this->success([
            'account' => $account,
        ]);
    }

    public function getAccountFirstUser(array $wordBody)
    {
        $account = $wordBody['account'];

        $user = AccountUtility::getAccountFirstUser($account);

        return $this->success([
            'user' => $user,
        ]);
    }

    public function getAccountLastUser(array $wordBody)
    {
        $account = $wordBody['account'];

        $user = AccountUtility::getAccountLastUser($account);

        return $this->success([
            'user' => $user,
        ]);
    }
}
