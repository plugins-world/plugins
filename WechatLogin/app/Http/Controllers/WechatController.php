<?php

namespace Plugins\WechatLogin\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Plugins\WechatLogin\Models\Account;
use ZhenMu\Support\Traits\ResponseTrait;
use Plugins\Aone\Utilities\WechatUtility;
use Plugins\WechatLogin\Models\AccountUser;
use Plugins\WechatLogin\Models\AccountConnect;
use Plugins\MarketManager\Utilities\StrUtility;
use Plugins\WechatLogin\Utilities\AccountUtility;
use Plugins\WechatLogin\Models\TenantAccountProfile;

class WechatController extends Controller
{
    use ResponseTrait;

    public function getJssdkConfig()
    {
        request()->validate([
            'app_id' => ['required', 'string'],
            'url' => ['nullable', 'string'],
            'jsApiList' => ['nullable', 'array'],
            'openTagList' => ['nullable', 'array'],
            'debug' => ['nullable', 'boolean:0,1'],
        ]);

        $appId = request('app_id');

        $url = request('url', request()->getHttpHost());
        $jsApiList = request('jsApiList', []);
        $openTagList = request('openTagList', []);
        $debug = request()->boolean('debug', false);

        $tenant = request()->attributes->get('tenant');
        $app = WechatUtility::getTenantApp($tenant, WechatUtility::TYPE_OFFICIAL_ACCOUNT, $appId);
        if (!$app) {
            return $this->fail('请先配置 app_id 等相关信息');
        }

        /** @var \EasyWeChat\OfficialAccount\Utils */
        $utils = $app->getUtils();

        $config = $utils->buildJsSdkConfig(
            $url,
            $jsApiList,
            $openTagList,
            $debug
        );

        return $this->success($config);
    }

    public function wechatAuthUrl()
    {
        request()->validate([
            'app_id' => ['required', 'string'],
            'callback_url' => ['required', 'url'],
        ]);

        $appId = request('app_id');
        $callbackUrl = request('callback_url');

        $tenant = request()->attributes->get('tenant');
        $app = WechatUtility::getTenantApp($tenant, WechatUtility::TYPE_OFFICIAL_ACCOUNT, $appId);
        if (!$app) {
            return $this->fail("请先配置 app_id {$appId} 相关信息");
        }

        $redirectUrl = route('wechat-official-login.callback', ['app_id' => $appId, 'callback_url' => $callbackUrl]);
        $oauth = $app->getOAuth();
        $redirectUrl = $oauth->scopes(['snsapi_userinfo'])->redirect($redirectUrl);

        return $this->success([
            'redirect_url' => $redirectUrl,
        ]);
    }

    public function wechatAuthCallback()
    {
        request()->validate([
            'app_id' => ['required', 'string'],
            'callback_url' => ['required', 'url'],
            'code' => ['required', 'string'],
            'state' => ['required', 'string'],
        ]);

        $callbackUrl = request('callback_url');
        $params = request()->except('callback_url');
        $redirectUrl = $callbackUrl . '?' . http_build_query($params);

        return redirect($redirectUrl);
    }

    public function wechatLoginByCode()
    {
        request()->validate([
            'app_id' => ['required', 'string'],
            'code' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'code_url' => ['required_without:code', 'url'],
        ]);

        $appId = request('app_id');
        $code = request('code');
        $state = request('state');
        $codeUrl = request('code_url');

        if ($codeUrl) {
            $codeUrlInfo = parse_url($codeUrl);

            if (empty($codeUrlInfo['query'] ?? null)) {
                return $this->fail("请提供正确的 code_url 地址");
            }

            parse_str($codeUrlInfo['query'], $codeUrlQueryInfo);

            $queryCode = $codeUrlQueryInfo['code'] ?? null;
            $queryState = $codeUrlQueryInfo['state'] ?? null;

            if (empty($queryCode)) {
                return $this->fail("code_url 地址的格式不正确，缺失 code 参数");
            }

            if (empty($state)) {
                $state = $queryState;
            }

            if (empty($code)) {
                $code = $queryCode;
            }
        }

        $tenant = request()->attributes->get('tenant');
        $app = WechatUtility::getTenantApp($tenant, WechatUtility::TYPE_OFFICIAL_ACCOUNT, $appId);
        if (!$app) {
            return $this->fail("请先配置 app_id {$appId} 相关信息");
        }

        $oauth = $app->getOAuth();
        // [
        //     "id" => "oROpE6bwtq48CaQ31AgJSmMbjGJc"
        //     "name" => "\u{3164}\u{3164}"
        //     "nickname" => "\u{3164}\u{3164}"
        //     "avatar" => "https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83erPtQMaSAg1unOjKWa7xqxYm7kWyiaicahD9V3voQkRDhPs21a3tOYeHGtO4fkfdQ6eOj0kBUz61iahA/132"
        //     "email" => null
        //     "raw" => array:9 [
        //       "openid" => "oROpE6bwtq48CaQ31AgJSmMbjGJc"
        //       "nickname" => "\u{3164}\u{3164}"
        //       "sex" => 0
        //       "language" => ""
        //       "city" => ""
        //       "province" => ""
        //       "country" => ""
        //       "headimgurl" => "https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83erPtQMaSAg1unOjKWa7xqxYm7kWyiaicahD9V3voQkRDhPs21a3tOYeHGtO4fkfdQ6eOj0kBUz61iahA/132"
        //       "privilege" => []
        //     ]
        //     "access_token" => "76_5P79Ox02iqSruFx3TEc1NeX2AcgRGV9cTjYn8HfCcPDjA3NRQZ6MJcuh6d-jOvHO67aDsr7r_wQD8akua62uUriEsxS8btVcWK7iHHMbthI"
        //     "refresh_token" => "76_MomqR4WHEa4WxYyW37ELIU6UKnQFTpr33fis1_usmpJDWma18XgLBK6chSHgcFq7oELy28IxMvArNvOvWm98SQzxxvi11RWra3alxMR3HDc"
        //     "expires_in" => 7200
        //     "token_response" => array:5 [
        //       "access_token" => "76_5P79Ox02iqSruFx3TEc1NeX2AcgRGV9cTjYn8HfCcPDjA3NRQZ6MJcuh6d-jOvHO67aDsr7r_wQD8akua62uUriEsxS8btVcWK7iHHMbthI"
        //       "expires_in" => 7200
        //       "refresh_token" => "76_MomqR4WHEa4WxYyW37ELIU6UKnQFTpr33fis1_usmpJDWma18XgLBK6chSHgcFq7oELy28IxMvArNvOvWm98SQzxxvi11RWra3alxMR3HDc"
        //       "openid" => "oROpE6bwtq48CaQ31AgJSmMbjGJc"
        //       "scope" => "snsapi_userinfo"
        //     ]
        //   ]
        $user = $oauth->userFromCode($code);

        $raw = $user->getRaw();
        $openid = $user->getId();
        $name = $user->getName();
        $nickname = $user->getNickname();
        $avatar = $user->getAvatar();
        $email = $user->getEmail();
        $access_token = $user->getAccessToken();
        $refresh_token = $user->getRefreshToken();
        $connect_platform_id = 24; // @see https://docs.fresns.cn/database/dictionary/connects.html
        $raw['app_id'] = $appId;

        $account_id = null;
        $token = null;
        $accountConnect = AccountConnect::where([
            'app_id' => $appId,
            'connect_platform_id' => $connect_platform_id,
            'connect_account_id' => $openid,
        ])->first();
        if (!$accountConnect) {
            $accountConnect = AccountConnect::create([
                'app_id' => $appId,
                'connect_platform_id' => $connect_platform_id,
                'connect_account_id' => $openid,
                'connect_token' => $access_token,
                'connect_refresh_token' => $refresh_token,
                'connect_username' => $name,
                'connect_nickname' => $nickname,
                'connect_avatar' => $avatar,
                'plugin_fskey' => 'WechatLogin',
                'is_enabled' => true,
                'more_json' => $raw,
            ]);
        } else {
            $account = null;
            $account_id = $accountConnect->account_id;

            // 将邮箱绑定到账号
            if (!$account_id && $email) {
                $account = Account::where('email', $email)->first();
                $account_id = $account?->id;

                $account && $accountConnect->update([
                    'account_id' => $account?->id,
                ]);
            }

            if (!$account) {
                $account = Account::find($account_id);
            }

            $resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountLastUser([
                'account' => $account,
            ]);
            $user = $resp->getData('user');

            $expiresAt = now()->addDays(7);
            $tokenName = 'api';
            $abalities = ['*'];
            $generateTokenResp = \FresnsCmdWord::plugin('WechatLogin')->generateTokenForUser([
                'user' => $user,
                'expiresAt' => $expiresAt,
                'tokenName' => $tokenName,
                'abalities' => $abalities,
            ]);
            if ($generateTokenResp->isErrorResponse()) {
                return $this->fail($generateTokenResp->getMessage(), $generateTokenResp->getCode());
            }

            $token = $generateTokenResp->getData('token');
        }

        $is_need_bind_mobile = !$account_id;

        return $this->success([
            'account_connect_id' => $accountConnect->id,
            'account_id' => $account_id,
            'is_need_bind_mobile' => $is_need_bind_mobile,
            'token' => $token,
        ]);
    }

    public function wechatLoginupdateProfile()
    {
        \request()->validate([
            'account_connect_id' => ['required', 'string'],
            'avatar' => ['nullable', 'string'],
        ]);

        $account_connect_id = \request('account_connect_id');
        $accountInfo = AccountUtility::getLoginAccount();

        $tenant = request()->attributes->get('tenant');
        $accountConnect = AccountConnect::find($account_connect_id);

        if (!$accountInfo) {
            return $this->fail('获取账户信息失败，请检查是否登录成功');
        }

        $accountConnect?->update([
            'account_id' => $accountInfo['account_id'],
        ]);

        // 创建租户用户信息
        $tenantAccountProfile = TenantAccountProfile::updateOrCreate([
            'tenant_no' => $tenant?->tenant_no,
            'account_id' => $accountInfo['account_id'],
            'user_id' => $accountInfo['user_id'],
        ], [
            'mobile' => $accountInfo['mobile'],
        ]);

        return $this->success([
            'tenant_account_profile_id' => $tenantAccountProfile?->id,
        ]);
    }

    public function miniAppLoginByCode()
    {
        \request()->validate([
            'app_id' => ['required', 'string'],
            'code' => ['required', 'string'],
        ]);

        $appId = \request('app_id');
        $code = \request('code');

        $tenant = request()->attributes->get('tenant');
        $app = WechatUtility::getTenantApp($tenant, WechatUtility::TYPE_MINI_PROGRAM, $appId);
        if (!$app) {
            return $this->fail("请先配置 app_id {$appId} 相关信息");
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
        $data['app_id'] = $appId;
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
            'connect_platform_id' => 25,
            'app_id' => $appId,
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
            'app_id' => ['required', 'string'],
            'account_connect_id' => ['required', 'integer'],
            'code' => ['required', 'string'],
            'encryptedData' => ['required', 'string'],
            'iv' => ['required', 'string'],
        ]);

        $appId = \request('app_id');
        $accountConnectId = \request('account_connect_id');
        $accountConnect = AccountConnect::where('connect_platform_id', 25)
            ->where('app_id', $appId)
            ->where('id', $accountConnectId)
            ->first();

        if (!$accountConnect) {
            return $this->fail("授权信息 account_connect_id: {$accountConnectId} 不存在");
        }

        $tenant = request()->attributes->get('tenant');
        $app = WechatUtility::getTenantApp($tenant, WechatUtility::TYPE_MINI_PROGRAM);
        if (!$app) {
            return $this->fail("请先配置 app_id {$appId} 相关信息");
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

        $systemConfig = WechatUtility::getConfig(WechatUtility::TYPE_MINI_PROGRAM, $appId);
        $systemConfigAppId = $systemConfig['app_id'] ?? null;
        $clientAppId = $session['watermark']['appid'] ?? null;
        WechatUtility::checkConfigAvaliable($systemConfigAppId, $clientAppId);

        $countryCode = $session['countryCode'];
        $phoneNumber = $session['phoneNumber'];
        $purePhoneNumber = $session['purePhoneNumber'];

        if (empty($accountConnect['account_id'])) {
            $data['type'] = 3; // 1-超级管理员;2-普通管理员;3-普通用户
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
                    'email',
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
            'app_id' => ['required', 'string'],
            'account_connect_id' => ['required', 'integer'],
            'avatar' => ['nullable', 'file'],
            'nickname' => ['nullable', 'string'],
        ]);

        $appId = request('app_id');
        $accountConnectId = request('account_connect_id');

        $user = auth()->user();
        if (!$user) {
            return $this->fail("未登录");
        }

        $accountUser = AccountUser::where('user_id', $user['id'])->first();
        if (!$accountUser) {
            return $this->fail("用户 {$user['id']} 未绑定账户信息");
        }

        $account = Account::where('id', $accountUser['account_id'])->first();
        if (!$account) {
            return $this->fail("未找到 {$accountUser['account_id']} 的账户信息");
        }

        $accountConnect = AccountConnect::where('connect_platform_id', 25)
            ->where('id', $accountConnectId)
            ->where('account_id', $account['id'])
            ->where('app_id', $appId)
            ->first();

        if (!$accountConnect) {
            return $this->fail("未找到 {$account['id']} 的用户授权信息");
        }

        if (\request()->file('avatar')?->isValid()) {
            $resp = \FresnsCmdWord::plugin('FileStorage')->upload([
                'type' => 'image',
                'usageType' => 'avatar',
                'file' => \request()->file('avatar'),
            ]);

            $avatar = $resp->getData('path');
        } else {
            $avatar = \request('avatar');
        }

        $newNickname = \request('nickname');
        $newAvatar = \request('avatar');

        if (\request()->has('nickname')) {
            event('wechat-login:user_info:update:nickname', [['account_connect_id' => $accountConnect?->id, 'nickname' => $newNickname]]);
        }
        if (\request()->has('avatar')) {
            event('wechat-login:user_info:update:avatar', [['account_connect_id' => $accountConnect?->id, 'avatar' => $newAvatar]]);
        }

        $nickname = $newNickname ?? $accountConnect?->connect_nickname ?? null;
        $avatar = $avatar ?? $accountConnect?->getRawOriginal('connect_avatar') ?? null;
        $accountConnect?->update([
            'connect_nickname' => $nickname,
            'connect_avatar' => $avatar,
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
