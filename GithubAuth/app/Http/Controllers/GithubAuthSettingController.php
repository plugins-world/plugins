<?php

namespace Plugins\GithubAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Plugins\LaravelConfig\Models\Config;
use Plugins\LaravelConfig\Utilities\ConfigUtility;

class GithubAuthSettingController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('GithubAuth::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView(Request $request)
    {
        config(['session.same_site' => 'none']);
        config(['session.secure' => uniqid()]);

        // code
        $itemKeys = [
            'client_id',
            'client_secret',
            'redirect',
            'is_enable_proxy',
            'proxy_http',
            'proxy_https',
        ];

        // $configs = Config::whereIn('item_key', $itemKeys)->where('item_tag', 'github_auth')->get();
        $configs = Config::getValueByKeys($itemKeys, 'github_auth');

        $defaultProxy = 'http://10.0.30.3:7890'; // 内网默认代理

        return view('GithubAuth::setting', [
            'configs' => $configs,
            'defaultProxy' => $defaultProxy,
        ]);
    }

    public function saveSetting(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'redirect' => 'required|url',
            'is_enable_proxy' => 'nullable|in:0,1',
            'proxy_http' => 'nullable|string',
            'proxy_https' => 'nullable|string',
        ]);

        $itemKeys = [
            'client_id',
            'client_secret',
            'redirect',
            'is_enable_proxy',
            'proxy_http',
            'proxy_https',
        ];

        // code
        // Config updateConfigs with $itemKeys and 'github_auth'
        ConfigUtility::updateConfigs($itemKeys, 'github_auth');

        return redirect(route('github-auth.setting'));
    }
}
