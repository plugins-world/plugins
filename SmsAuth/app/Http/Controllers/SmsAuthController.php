<?php

namespace Plugins\SmsAuth\Http\Controllers;

use Illuminate\Routing\Controller;
use Plugins\MarketManager\Utils\LaravelCache;
use ZhenMu\Support\Traits\ResponseTrait;

class SmsAuthController extends Controller
{
    use ResponseTrait;

    public function sendCode()
    {
        request()->validate([
            'mobile' => ['required', 'string'],

            'fskey' => ['required', 'string'],
            'cmdWord' => ['nullable', 'string'],
        ]);
        // 要发送验证码的手机号
        $mobile = request('mobile');

        // 业务插件信息
        $fskey = request('fskey');
        $cmdWord = request('cmdWord', 'handleSmsAction');

        // 短信验证码 5 分钟有效
        $codeCacheTime = now()->addMinutes(5);
        $sendCodeActionCacheTime = now()->addMinutes(1);

        // 获取5分钟内的验证码缓存 key
        $code_cache_mobile = 'sms_mobile:'.$mobile;
        $code_cache_key = LaravelCache::remember($code_cache_mobile, $codeCacheTime, function () {
            $code_cache_key = uniqid();
            return $code_cache_key;
        });

        // 生成验证码
        $code = LaravelCache::remember($code_cache_key, $codeCacheTime, function () use ($code_cache_key, $codeCacheTime) {
            $code = (string) random_int(100000, 999999);
            info(sprintf('code_cache_key: %s, cache_code: %s, expire_at: %s', $code_cache_key, $code, $codeCacheTime));
            return $code;
        });

        $send_sms_cache_key = 'send_sms:'.$code_cache_key;
        $resp = LaravelCache::remember($send_sms_cache_key, $sendCodeActionCacheTime, function () use ($mobile, $code, $fskey, $cmdWord) {
            $params = [
                'code' => $code,
            ];

            // 发短信
            $to = $mobile;
            $wordBody = [
                'rpc' => [
                    'fskey' => $fskey,
                    'cmdWord' => $cmdWord,
                    'wordBody' => [
                        'actionType' => 'login',
                        'to' => $to,
                        'data' => $params,
                    ],
                ]
            ];

            $resp = \FresnsCmdWord::plugin('SmsAuth')->sendCode($wordBody);

            return $resp ?? null;
        });

        if ($resp?->isErrorResponse()) {
            LaravelCache::forget($code_cache_key);
            return $this->fail($resp->getMessage(), $resp->getCode());
        }

        return $this->success([
            'code_cache_key' => $code_cache_key,
        ]);
    }

    public function verifyCode()
    {
        request()->validate([
            'code_cache_key' => ['required', 'string'],
            'user_sms_code' => ['required', 'string'],
        ]);

        $code_cache_key = request('code_cache_key');
        $user_sms_code = request('user_sms_code');

        // 获取缓存中的 code
        $cache_code = LaravelCache::get($code_cache_key);

        if (is_null($cache_code)) {
            return $this->fail('验证码已过期');
        }

        // 验证结果缓存 5 分钟
        $cacheTime = now()->addMinutes(5);
        $validate_cache_key = 'sms_code:validate:result:' . $code_cache_key;
        $validate_result = LaravelCache::remember($validate_cache_key, $cacheTime, function () use ($cache_code, $user_sms_code) {
            return strval($cache_code) === strval($user_sms_code);
        });

        if (is_null($validate_result)) {
            return $this->fail('验证码已过期');
        }

        if (!$validate_result) {
            return $this->fail('验证码不正确');
        }

        return $this->success([
            'code_cache_key' => $code_cache_key,
        ]);
    }

    public function login()
    {
        request()->validate([
            'code_cache_key' => ['required', 'string'],
            'mobile' => ['required', 'string'],
            'country_code' => ['nullable', 'string'],
        ]);

        $code_cache_key = request('code_cache_key');
        $mobile = request('mobile');
        $country_code = request('country_code');

        $validate_cache_key = 'sms_code:validate:result:' . $code_cache_key;
        $validate_result = LaravelCache::get($validate_cache_key);
        if (is_null($validate_result)) {
            return $this->fail('验证码未进行验证或验证码已过期');
        }

        if (!$validate_result) {
            return $this->fail('验证码不正确');
        }

        // 数据入库
        $addAccountResp = \FresnsCmdWord::plugin('WechatLogin')->addAccount([
            'type' => 3,
            'aid' => null,
            'country_code' => $country_code,
            'pure_phone' => $mobile,
            'phone' => $mobile,
            'email' => null,
            'password' => null,
        ]);
        if ($addAccountResp->isErrorResponse()) {
            return $this->fail($addAccountResp->getMessage(), $addAccountResp->getCode());
        }

        // 生成 api token
        $user = $addAccountResp->getData('user');
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

        // 清除 code 缓存
        $code_cache_mobile = 'sms_mobile:'.$mobile;
        $send_sms_cache_key = 'send_sms:'.$code_cache_key;
        LaravelCache::forget($code_cache_mobile);
        LaravelCache::forget($code_cache_key);
        LaravelCache::forget($validate_cache_key);
        LaravelCache::forget($send_sms_cache_key);

        return $this->success([
            'token' => $token,
        ]);
    }
}
