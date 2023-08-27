<?php

namespace Plugins\WechatLogin\Utilities;

use Plugins\WechatLogin\Models\AccountConnect;
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

    public static function getAccountConnect($account, int $connect_platform_id)
    {
        if (!$account) {
            return null;
        }

        $accountConnect = AccountConnect::query()
            ->where('account_id', $account['id'])
            ->where('connect_platform_id', $connect_platform_id)
            ->first();

        if (!$accountConnect) {
            return null;
        }

        return $accountConnect;
    }
}
