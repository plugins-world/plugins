<?php

namespace Plugins\WechatLogin\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Plugins\WechatLogin\Models\Account;
use ZhenMu\Support\Traits\ResponseTrait;
use Plugins\WechatLogin\Models\AccountUser;
use Plugins\WechatLogin\Models\AccountConnect;
use Plugins\MarketManager\Utilities\StrUtility;
use Plugins\WechatLogin\Utilities\WechatUtility;

class WechatLoginController extends Controller
{
    use ResponseTrait;

    public function miniAppLoginCode()
    {
        \request()->validate([
            'code' => ['required', 'string'],
        ]);

        $code = \request('code');
        $app = WechatUtility::getApp(WechatUtility::TYPE_MINI_PROGRAM);
        if (!$app) {
            return $this->fail('请先配置小程序信息');
        }

        /** @var \EasyWeChat\MiniApp\Utils */
        $utils = $app->getUtils();

        try {
            $response = $utils->codeToSession($code);
            // $response = [
            //     "session_key" => "xxx"
            //     "openid" => "xxxxx"
            // ]
        } catch (\Throwable $e) {
            WechatUtility::checkCodeUsed($e, $code);

            return $this->fail($e->getMessage(), $e->getCode());
        }

        $data['account_id'] = null;
        $data['connect_platform_id'] = 25; // @see https://docs.fresns.cn/database/dictionary/connects.html
        $data['connect_account_id'] = $response['openid'];
        $data['connect_token'] = null;
        $data['connect_refresh_token'] = null;
        $data['connect_username'] = null;
        $data['connect_nickname'] = null;
        $data['connect_avatar'] = null;
        $data['plugin_fskey'] = 'WechatLogin';
        $data['more_json']['session_key'] = $response['session_key'];
        $data['is_enabled'] = true;

        $accountConnect = AccountConnect::where([
            'plugin_fskey' => $data['plugin_fskey'],
            'connect_account_id' => $data['connect_account_id'],
        ])->first();
        if ($accountConnect) {
            $data['account_id'] = $accountConnect['account_id'];
            $data['connect_refresh_token'] = $accountConnect['connect_refresh_token'];
            $data['connect_username'] = $accountConnect['connect_username'];
            $data['connect_nickname'] = $accountConnect['connect_nickname'];
            $data['connect_avatar'] = $accountConnect->getRawOriginal('connect_avatar');
            $data['is_enabled'] = $accountConnect['is_enabled'];

            $accountConnect->update($data);
        } else {
            $accountConnect = AccountConnect::create($data);
        }

        return $this->success([
            'account_id' => $accountConnect['account_id'],
            'account_connect_id' => $accountConnect['id'],
            'connect_username' => $accountConnect['connect_username'],
            'connect_nickname' => $accountConnect['connect_nickname'],
            'connect_avatar' => $accountConnect['connect_avatar'],
            'mobile' => null,
        ]);
    }

    public function miniAppBindPhone()
    {
        \request()->validate([
            'account_connect_id' => ['required', 'integer'],
            'code' => ['required', 'string'],
            'encryptedData' => ['required', 'string'],
            'iv' => ['required', 'string'],
        ]);

        $accountConnectId = \request('account_connect_id');
        $accountConnect = AccountConnect::where('connect_platform_id', 25)->where('id', $accountConnectId)->first();
        throw_if(!$accountConnect, "授权信息 account_connect_id: {$accountConnectId} 不存在");

        $app = WechatUtility::getApp(WechatUtility::TYPE_MINI_PROGRAM);
        if (!$app) {
            return $this->fail('请先配置小程序信息');
        }

        /** @var \EasyWeChat\MiniApp\Utils */
        $utils = $app->getUtils();

        $sessionKey = $accountConnect['more_json']['session_key'] ?? '';
        $iv = \request('iv');
        $encryptedData = \request('encryptedData');

        $session = $utils->decryptSession($sessionKey, $iv, $encryptedData);
        // $session = [
        //     "phoneNumber" => "133xxx33"
        //     "purePhoneNumber" => "133xxx33"
        //     "countryCode" => "86"
        //     "watermark" => array:2 [
        //       "timestamp" => 1688393581
        //       "appid" => "wx92xxxd57a"
        //     ]
        // ]

        $systemConfigAppId = WechatUtility::getConfig(WechatUtility::TYPE_MINI_PROGRAM)['app_id'] ?? null;
        $clientAppId = $session['watermark']['appid'] ?? null;
        WechatUtility::checkConfigAvaliable($systemConfigAppId, $clientAppId);

        $countryCode = $session['countryCode'];
        $phoneNumber = $session['phoneNumber'];
        $purePhoneNumber = $session['purePhoneNumber'];

        if (empty($accountConnect['account_id'])) {
            $data['type'] = 1;
            $data['country_code'] = $countryCode;
            $data['pure_phone'] = $purePhoneNumber;
            $data['phone'] = $phoneNumber;
            $data['email'] = null;
            $data['password'] = null;
            $data['last_login_at'] = now();
            $data['is_verify'] = false;
            $data['verify_plugin_fskey'] = null;
            $data['verify_real_name'] = null;
            $data['verify_gender'] = 1;
            $data['verify_cert_type'] = null;
            $data['verify_cert_number'] = null;
            $data['verify_identity_type'] = null;
            $data['verify_at'] = null;
            $data['verify_log'] = null;
            $data['is_enabled'] = true;
            $data['wait_delete'] = false;
            $data['wait_delete_at'] = null;

            $account = Account::where('pure_phone', $purePhoneNumber)->first();
            if (!$account) {
                $account = Account::create($data);
            } else {
                $attrs = collect($data)->only([
                    'country_code',
                    'pure_phone',
                    'phone',
                ])->all();

                $account->update($attrs);
            }

            $accountConnect->update([
                'account_id' => $account['id'],
            ]);
        } else {
            $account = Account::find($accountConnect['account_id']);
        }
        throw_if(!$account, '未找到注册用户，登录失败');

        $accountUser = AccountUser::where('account_id', $account['id'])->first();
        if (empty($accountUser)) {
            $userAttrs['name'] = uniqid();
            $userAttrs['email'] = $userAttrs['name'] . "@example.com";
            $userAttrs['password'] = Hash::make($account['aid'] . '168');

            $user = User::create($userAttrs);

            $accountUser = AccountUser::create([
                'user_id' => $user['id'],
                'account_id' => $account['id'],
            ]);
        } else {
            $user = User::find($accountUser['user_id']);
        }

        $token = $this->generateTokenForUser($user);

        return $this->success([
            'token' => $token,
            'account_id' => $accountConnect['account_id'],
            'account_connect_id' => $accountConnect['id'],
            'connect_username' => $accountConnect['connect_username'],
            'connect_nickname' => $accountConnect['connect_nickname'],
            'connect_avatar' => $accountConnect['connect_avatar'],
            'mobile' => StrUtility::maskNumber($account['pure_phone']),
        ]);
    }

    protected function generateTokenForUser($user)
    {
        $tokenName = \request('token_name') ?? 'wechat_login_api_mini_app';
        $abalities = ['*'];
        $expiresAt = now()->addDays(7);

        $token = $user->createToken($tokenName, $abalities, $expiresAt);

        return $token->plainTextToken;
    }

    public function miniAppUpdateUserInfo()
    {
        \request()->validate([
            'avatar' => ['nullable', 'file'],
            'nickname' => ['nullable', 'string'],
        ]);

        $user = auth()->user();
        throw_if(!$user, '未登录');

        $accountUser = AccountUser::where('user_id', $user['id'])->first();
        throw_if(!$accountUser, "用户 {$user['id']} 未绑定账户信息");

        $account = Account::where('id', $accountUser['account_id'])->first();
        throw_if(!$account, "未找到 {$accountUser['account_id']} 的账户信息");

        $accountConnect = AccountConnect::where('connect_platform_id', 25)->where('account_id', $account['id'])->first();
        throw_if(!$accountConnect, "未找到 {$account['id']} 的用户授权信息");

        if (\request()->file('avatar')?->isValid()) {
            $resp = \FresnsCmdWord::plugin('FileStorage')->upload([
                'type' => 'image',
                'usageType' => 'avatar',
                'file' => \request()->file('avatar'),
                'disk' => 'cos',
            ]);

            $avatar = $resp->getData('path');
        } else {
            $avatar = \request('avatar');
        }

        $accountConnect?->update([
            'connect_nickname' => \request('nickname') ?? $accountConnect?->connect_nickname ?? null,
            'connect_avatar' => $avatar ?? $accountConnect?->getRawOriginal('connect_avatar') ?? null,
        ]);

        return $this->success([
            'account_id' => $accountConnect['account_id'],
            'account_connect_id' => $accountConnect['id'],
            'connect_username' => $accountConnect['connect_username'],
            'connect_nickname' => $accountConnect['connect_nickname'],
            'connect_avatar' => $accountConnect['connect_avatar'],
            'mobile' => StrUtility::maskNumber($account['pure_phone']),
        ]);
    }
}
