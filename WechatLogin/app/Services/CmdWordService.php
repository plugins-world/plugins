<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\WechatLogin\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Plugins\WechatLogin\Models\Account;
use Plugins\WechatLogin\Models\AccountUser;
use Plugins\WechatLogin\Utilities\AccountUtility;
use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function addAccount(array $wordBody)
    {
        $data['type'] = $wordBody['type'] ?? 3; // 1.超级管理员 / 2.普通管理员 / 3.普通用户
        $data['aid'] = $wordBody['aid'] ?? null;
        $data['country_code'] = $wordBody['country_code'] ?? null;
        $data['pure_phone'] = $wordBody['pure_phone'] ?? null;
        $data['phone'] = $wordBody['pure_phone'] ?? null;
        $data['email'] = $wordBody['email'] ?? null;
        $data['password'] = $wordBody['password'] ?? null;
        $data['last_login_at'] = now();
        $data['is_verify'] = false;
        $data['verify_plugin_fskey'] = null;
        $data['verify_real_name'] = null; // 1.未知 / 2.男 / 3.女
        $data['verify_gender'] = 1;
        $data['verify_cert_type'] = null;
        $data['verify_cert_number'] = null;
        $data['verify_identity_type'] = null;
        $data['verify_at'] = null;
        $data['verify_log'] = null;
        $data['is_enabled'] = true;
        $data['wait_delete'] = false;
        $data['wait_delete_at'] = null;

        $account = Account::where('pure_phone', $data['pure_phone'])->first();
        if (!$account) {
            $account = Account::create($data);
        } else {
            $attrs = collect($data)->only([
                'country_code',
                'pure_phone',
                'phone',
                'email',
            ])->all();

            $account->update($attrs);
        }

        $accountUser = AccountUser::where('account_id', $account['id'])->first();
        if (empty($accountUser)) {
            $userAttrs['name'] = uniqid();
            $userAttrs['email'] = $userAttrs['name'] . "@example.com";
            $userAttrs['password'] = Hash::make($account['aid'] . '168');
            if ($data['password']) {
                $userAttrs['password'] = $account->password;
            }
            if ($data['email']) {
                $userAttrs['email'] = $account->email;
            }

            $user = User::create($userAttrs);

            $accountUser = AccountUser::updateOrCreate([
                'user_id' => $user['id'],
                'account_id' => $account['id'],
            ]);
        } else {
            $user = User::find($accountUser['user_id']);
        }

        return $this->success([
            'user' => $user,
            'accountUser' => $accountUser,
            'account' => $account,
        ]);
    }

    public function addUser(array $wordBody)
    {
        $account['id'] = $wordBody['account_id'] ?? null;
        $account['aid'] = $wordBody['aid'] ?? uniqid();
        $account['password'] = $wordBody['password'] ?? $account['aid'] . '168';

        $userAttrs['name'] = $wordBody['name'] ?? uniqid();
        $userAttrs['email'] = $userAttrs['email'] ?? $userAttrs['name'] . "@example.com";
        $userAttrs['password'] = Hash::make($account['password']);

        $user = User::create($userAttrs);

        if ($account['id']) {
            $accountUser = AccountUser::updateOrCreate([
                'user_id' => $user['id'],
                'account_id' => $account['id'],
            ]);
        }

        return $this->success([
            'user' => $user,
            'accountUser' => $accountUser,
        ]);
    }

    public function generateTokenForUser(array $wordBody)
    {
        $user = $wordBody['user'];
        $expiresAt = $wordBody['expiresAt'] ?? null;
        $tokenName = $wordBody['tokenName'] ?? 'api';
        $abalities = $wordBody['abalities'] ?? ['*'];

        $token = AccountUtility::generateTokenForUser($user, $expiresAt, $tokenName, $abalities);

        return $this->success([
            'token' => $token,
        ]);
    }

    public function getAccountOfUser(array $wordBody)
    {
        $user = $wordBody['user'];

        $account = AccountUtility::getAccountOfUser($user);

        return $this->success([
            'account' => $account,
        ]);
    }

    public function getAccountByAccountId(array $wordBody)
    {
        $accountId = $wordBody['accountId'] ?? null;

        $account = AccountUtility::getAccountByAccountId($accountId);

        return $this->success([
            'account' => $account,
        ]);
    }

    public function getAccountByAId(array $wordBody)
    {
        $aid = $wordBody['aid'];

        $account = AccountUtility::getAccountByAId($aid);

        return $this->success([
            'account' => $account,
        ]);
    }

    public function getAccountByMobile(array $wordBody)
    {
        $mobile = $wordBody['mobile'];

        $account = AccountUtility::getAccountByMobile($mobile);

        return $this->success([
            'account' => $account,
        ]);
    }

    public function getAccountByEmail(array $wordBody)
    {
        $email = $wordBody['email'];

        $account = AccountUtility::getAccountByEmail($email);

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

    public function getAccountConnect(array $wordBody)
    {
        $account = $wordBody['account'];
        $connect_platform_id = $wordBody['connect_platform_id'];

        $accountConnect = AccountUtility::getAccountConnect($account, $connect_platform_id);

        return $this->success([
            'accountConnect' => $accountConnect,
        ]);
    }

    public function getAccountConnectOfUser(array $wordBody)
    {
        $user = $wordBody['user'];
        $connect_platform_id = $wordBody['connect_platform_id'];

        $accountConnect = AccountUtility::getAccountConnectOfUser($user, $connect_platform_id);

        return $this->success([
            'accountConnect' => $accountConnect,
        ]);
    }

    public function loadAccountBaseInfo(array $wordBody)
    {
        $baseInfo = $wordBody['baseInfo'];
        $account = $wordBody['account'];
        $connect_platform_id = $wordBody['connect_platform_id'] ?? 25;

        $newBaseInfo = AccountUtility::loadAccountBaseInfo($baseInfo, $account, $connect_platform_id);

        return $this->success([
            'newBaseInfo' => $newBaseInfo,
        ]);
    }
}
