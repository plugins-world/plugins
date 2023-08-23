<?php

namespace Plugins\WechatLogin\Utilities;

use Plugins\WechatLogin\Models\AccountUser;

class AccountUtility
{
    public static function getAccountOfUser($user)
    {
        if (!$user) {
            return null;
        }

        $accountUser = AccountUser::where('user_id', $user['id'])->first();
        if (!$accountUser) {
            return null;
        }

        return $accountUser->account;
    }

    public static function getAccountFirstUser($account)
    {
        if (!$account) {
            return null;
        }

        $accountUser = AccountUser::where('account_id', $account['id'])->first();
        if (!$accountUser) {
            return null;
        }

        return $accountUser->user;
    }

    public static function getAccountLastUser($account)
    {
        if (!$account) {
            return null;
        }

        $accountUser = AccountUser::where('account_id', $account['id'])->orderByDesc('id')->first();
        if (!$accountUser) {
            return null;
        }

        return $accountUser->user;
    }
}
