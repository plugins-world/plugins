<?php

namespace Plugins\DemoTest\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fresns\DemoTest\Models\DemoTest;
use MouYong\LaravelConfig\Models\Config;

class DemoTestController extends Controller
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

        return view('DemoTest::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView()
    {
        $configs = Config::getValueByKeys([
            // item_key1,
            // item_key2,
        ]);

        return view('DemoTest::setting', [
            'configs' => $configs,
        ]);
    }

    public function saveSetting()
    {
        \request()->validate([
            // 'item_key1' => 'required|url',
            // 'item_key2' => 'nullable|url',
        ]);

        $itemKeys = [
            // item_key1,
            // item_key2,
        ];

        // Config::updateConfigs($itemKeys, 'demo_test');

        return redirect(route('demo-test.setting'));
    }
}
