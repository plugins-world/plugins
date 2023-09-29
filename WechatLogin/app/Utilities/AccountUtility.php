<?php

namespace Plugins\WechatLogin\Utilities;

use Plugins\WechatLogin\Models\Account;
use Plugins\WechatLogin\Models\AccountUser;
use Plugins\WechatLogin\Models\AccountConnect;

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

    public static function getAccountByAccountId(?int $accountId)
    {
        if (!$accountId) {
            return null;
        }

        $account = Account::where('id', $accountId)->first();
        if (!$account) {
            return null;
        }

        return $account;
    }

    public static function getAccountByAId(?string $aid)
    {
        if (!$aid) {
            return null;
        }

        $account = Account::where('aid', $aid)->first();
        if (!$account) {
            return null;
        }

        return $account;
    }

    public static function getAccountByMobile(?string $mobile)
    {
        if (!$mobile) {
            return null;
        }

        $account = Account::where('pure_phone', $mobile)->first();
        if (!$account) {
            return null;
        }

        return $account;
    }

    public static function getAccountByEmail(?string $email)
    {
        if (!$email) {
            return null;
        }

        $account = Account::where('email', $email)->first();
        if (!$account) {
            return null;
        }

        return $account;
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

    public static function generateTokenForUser($user, $expiresAt = null, $tokenName = 'api', $abalities = ['*'])
    {
        $expiresAt = $expiresAt ?? now()->addDays(7);

        $token = $user?->createToken($tokenName, $abalities, $expiresAt);

        return $token?->plainTextToken;
    }
}
