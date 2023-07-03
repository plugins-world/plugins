<?php

namespace Plugins\WechatLogin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Plugins\LaravelConfig\Models\Config;
use Plugins\MarketManager\Utilities\PluginUtility;

class WechatLoginSettingController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('WechatLogin::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView(Request $request)
    {
        config(['session.same_site' => 'none']);
        config(['session.secure' => uniqid()]);

        // code
        $itemKeys = [
            'wechat_login_official_account',
            'wechat_login_mini_program',
            'wechat_login_open_platform',
        ];

        $configs = Config::whereIn('item_key', $itemKeys)->where('item_tag', 'wechat_login')->get();

        $officialAccount = $configs->where('item_key', 'wechat_login_official_account')->first()?->item_value ?? [];
        $miniProgram = $configs->where('item_key', 'wechat_login_mini_program')->first()?->item_value ?? [];
        $openPlatform = $configs->where('item_key', 'wechat_login_open_platform')->first()?->item_value ?? [];

        $version = PluginUtility::fresnsPluginVersionByFskey('WechatLogin');

        return view('WechatLogin::setting', compact('version', 'officialAccount', 'miniProgram', 'openPlatform'));
    }

    public function saveSetting(Request $request)
    {
        $request->validate([
            'wechat_login_official_account' => 'nullable|array',
            'wechat_login_mini_program' => 'nullable|array',
            'wechat_login_open_platform' => 'nullable|array',
        ]);

        if ($request->officialAccount) {
            Config::updateOrCreate([
                'item_key' => 'wechat_login_official_account',
            ], [
                'item_value' => $request->officialAccount,
                'item_type' => 'object',
                'item_tag' => 'wechat_login',
            ]);
            Config::forgetCache('wechat_login_official_account');
        }

        if ($request->miniProgram) {
            Config::updateOrCreate([
                'item_key' => 'wechat_login_mini_program',
            ], [
                'item_value' => $request->miniProgram,
                'item_type' => 'object',
                'item_tag' => 'wechat_login',
            ]);
            Config::forgetCache('wechat_login_mini_program');
        }

        if ($request->openPlatform) {
            Config::updateOrCreate([
                'item_key' => 'wechat_login_open_platform',
            ], [
                'item_value' => $request->openPlatform,
                'item_type' => 'object',
                'item_tag' => 'wechat_login',
            ]);
            Config::forgetCache('wechat_login_open_platform');
        }

        return redirect(route('wechat-login.setting'));
    }
}
