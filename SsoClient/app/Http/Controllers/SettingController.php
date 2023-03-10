<?php

namespace Plugins\SsoClient\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fresns\MarketManager\Models\Plugin;
use MouYong\LaravelConfig\Models\Config;

class SettingController extends Controller
{
    public function index()
    {
        $configs = Config::getValueByKeys([
            // item_key1,
            // item_key2,
        ]);

        $where = [];
        if (\request()->has('is_enable')) {
            $where['is_enable'] = \request('is_enable');
        }

        // $data = DemoTest::query()->where($where)->get();

        return view('SsoClient::index', [
            'configs' => $configs,
        ]);
    }
    
    public function showSettingPage()
    {
        $configs = Config::getValueByKeys([
            'sso_server_host',
            'sso_update_userinfo_service',
        ]);

        $pluginScenes = [
            'sso_update_userinfo_service',
        ];

        $plugins = Plugin::all();

        $pluginParams = [];
        foreach ($pluginScenes as $scene) {
            $pluginParams[$scene] = $plugins->filter(function ($plugin) use ($scene) {
                return in_array($scene, $plugin->scene);
            });
        }

        return view('SsoClient::setting', [
            'configs' => $configs,
            'plugins' => $pluginParams,
        ]);
    }

    public function save()
    {
        \request()->validate([
            'sso_server_host' => 'required',
            'sso_update_userinfo_service' => 'nullable',
        ]);

        $keys = [
            'sso_server_host',
            'sso_update_userinfo_service',
        ];

        foreach ($keys as $key) {
            Config::addConfig([
                'item_tag' => 'sso_client',
                'item_key' => $key,
                'item_type' => 'string',
                'item_value' => \request($key),
            ]);
        }

        return redirect(route('sso-client.setting'))->with([
            'tips' => '操作成功',
        ]);
    }
}
