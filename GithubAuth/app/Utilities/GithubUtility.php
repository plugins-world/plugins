<?php

namespace Plugins\GithubAuth\Utilities;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Plugins\WechatLogin\Models\Account;
use Laravel\Socialite\Facades\Socialite;
use Plugins\LaravelConfig\Models\Config;
use Plugins\WechatLogin\Models\AccountUser;
use Plugins\WechatLogin\Models\AccountConnect;

class GithubUtility
{
    public static function getHttpClient()
    {
        $configs = Config::getValueByKeys([
            'is_enable_proxy',
            'proxy_http',
            'proxy_https',
        ]);

        $options = [
            'base_uri' => null,
            'timeout' => 120, // Request 15s timeout
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];

        if ($configs['is_enable_proxy']) {
            $options['proxy'] = [
                'http' => $configs['proxy_http'] ?? null,
                'https' => $configs['proxy_https'] ?? null,
            ];
        }

        return new Client($options);
    }

    public static function initConfig()
    {
        $configs = Config::getValueByKeys([
            'client_id',
            'client_secret',
            'redirect',
        ]);

        config([
            'services.github' => $configs,
        ]);

        return $configs;
    }

    public static function redirect($url = null)
    {
        GithubUtility::initConfig();

        try {
            $driver = Socialite::driver('github')->setHttpClient(static::getHttpClient());
        } catch (\Throwable $e) {
            throw new \RuntimeException('获取授权链接失败，请稍后再试，原因: ' . $e->getMessage());
        }

        if ($url) {
            $driver->redirectUrl($url);
        }

        return $driver->redirect();
    }

    public static function callback()
    {
        GithubUtility::initConfig();

        // $user = {#530 ▼ // extensions/plugins/GithubAuth/app/Http/Controllers/Web/AuthController.php:35
        //     +id: 10336437
        //     +nickname: "mouyong"
        //     +name: "= - ="
        //     +email: "my24251325@gmail.com"
        //     +avatar: "https://avatars.githubusercontent.com/u/10336437?v=4"
        //     +user: array:32 [▼
        //         "login" => "mouyong"
        //         "id" => 10336437
        //         "node_id" => "MDQ6VXNlcjEwMzM2NDM3"
        //         "avatar_url" => "https://avatars.githubusercontent.com/u/10336437?v=4"
        //         "gravatar_id" => ""
        //         "url" => "https://api.github.com/users/mouyong"
        //         "html_url" => "https://github.com/mouyong"
        //         "followers_url" => "https://api.github.com/users/mouyong/followers"
        //         "following_url" => "https://api.github.com/users/mouyong/following{/other_user}"
        //         "gists_url" => "https://api.github.com/users/mouyong/gists{/gist_id}"
        //         "starred_url" => "https://api.github.com/users/mouyong/starred{/owner}{/repo}"
        //         "subscriptions_url" => "https://api.github.com/users/mouyong/subscriptions"
        //         "organizations_url" => "https://api.github.com/users/mouyong/orgs"
        //         "repos_url" => "https://api.github.com/users/mouyong/repos"
        //         "events_url" => "https://api.github.com/users/mouyong/events{/privacy}"
        //         "received_events_url" => "https://api.github.com/users/mouyong/received_events"
        //         "type" => "User"
        //         "site_admin" => false
        //         "name" => "= - ="
        //         "company" => null
        //         "blog" => "https://marketplace.plugins-world.cn"
        //         "location" => "China"
        //         "email" => "my24251325@gmail.com"
        //         "hireable" => null
        //         "bio" => null
        //         "twitter_username" => null
        //         "public_repos" => 137
        //         "public_gists" => 18
        //         "followers" => 66
        //         "following" => 102
        //         "created_at" => "2014-12-29T06:18:11Z"
        //         "updated_at" => "2023-08-16T05:48:54Z"
        //     ]
        //     +attributes: array:5 [▼
        //         "id" => 10336437
        //         "nickname" => "mouyong"
        //         "name" => "= - ="
        //         "email" => "my24251325@gmail.com"
        //         "avatar" => "https://avatars.githubusercontent.com/u/10336437?v=4"
        //     ]
        //     +token: "gho_nFftACxxxxxd4cK964HFY3q"
        //     +refreshToken: null
        //     +expiresIn: null
        //     +approvedScopes: array:1 [▼
        //         0 => "user:email"
        //     ]
        // }
        try {
            $githubUser = Socialite::driver('github')->setHttpClient(static::getHttpClient())->user();
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            if (request('error')) {
                $message = sprintf('error: %s, error_description: %s, server error: %s', request('error'), request('error_description'), $message);
            }

            throw new \RuntimeException('登录失败，原因：' . $message);
        }

        $githubUser = (array) $githubUser;

        $accountConnect = GithubUtility::connect($githubUser);

        return $accountConnect;
    }

    public static function connect(array $githubUser)
    {
        $data['account_id'] = null;
        $data['connect_platform_id'] = 4; // @see https://docs.fresns.cn/database/dictionary/connects.html
        $data['connect_account_id'] = $githubUser['id'];
        $data['connect_token'] = $githubUser['token'];
        $data['connect_refresh_token'] = $githubUser['refreshToken'];
        $data['connect_username'] = $githubUser['nickname'];
        $data['connect_nickname'] = $githubUser['name'];
        $data['connect_avatar'] = $githubUser['avatar'];
        $data['plugin_fskey'] = 'GithubAuth';
        $data['more_json'] = $githubUser;
        $data['is_enabled'] = true;

        $accountConnect = AccountConnect::where([
            'connect_platform_id' => 4,
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

        GithubUtility::createAccount($accountConnect, $githubUser);

        return $accountConnect;
    }

    public static function createAccount($accountConnect, array $githubUser)
    {
        if (empty($accountConnect['account_id'])) {
            $data['type'] = 1;
            $data['country_code'] = null;
            $data['pure_phone'] = null;
            $data['phone'] = null;
            $data['email'] = $githubUser['email'];
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

            $account = Account::where('email', $data['email'])->first();
            if (!$account) {
                $account = Account::create($data);
            } else {
                $attrs = collect($data)->only([
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

        $accountUser = AccountUser::where('account_id', $account['id'])->first();

        $userAttrs['name'] = $accountConnect['connect_nickname'];
        $userAttrs['email'] = $account['email'] ?? $userAttrs['name'] . "@example.com";

        if (empty($accountUser)) {
            $userAttrs['password'] = Hash::make($account['aid'] . '168');

            $user = User::create($userAttrs);

            $accountUser = AccountUser::create([
                'user_id' => $user['id'],
                'account_id' => $account['id'],
            ]);
        } else {
            $user = User::find($accountUser['user_id']);

            $user->update($userAttrs);
        }

        return $account;
    }

    public static function loginWeb($accountConnect, $guard = null)
    {
        $user = $accountConnect?->account->firstUser();
        if ($user) {
            auth($guard)->login($user);
        }
    }
}
