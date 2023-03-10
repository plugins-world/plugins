<?php

namespace Plugins\SsoServer\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        return view('SsoServer::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingPage()
    {
        $configs = Config::getValueByKeys([
            'sso_cookie_prefix',
        ]);

        return view('SsoServer::setting', [
            'configs' => $configs,
        ]);
    }

    public function save()
    {
        \request()->validate([
            'sso_cookie_prefix' => 'nullable',
        ]);

        Config::addConfig([
            'item_tag' => 'sso_server',
            'item_key' => 'sso_cookie_prefix',
            'item_type' => 'string',
            'item_value' => \request('sso_cookie_prefix'),
        ]);

        return redirect(route('sso-server.setting'))->with([
            'tips' => '操作成功',
        ]);
    }
}
