<?php

namespace Plugins\Tenant\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fresns\Tenant\Models\Tenant;
use MouYong\LaravelConfig\Models\Config;

class TenantController extends Controller
{
    public function index()
    {
        $configs = Config::getValueByKeys([
            // item_key1,
            // item_key2,
        ]);

        $where = [];
        if (\request()->has('is_enabled')) {
            $where['is_enabled'] = \request('is_enabled');
        }

        // $data = Tenant::query()->where($where)->get();

        return view('Tenant::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView()
    {
        $configs = Config::getValueByKeys([
            // item_key1,
            // item_key2,
        ]);

        return view('Tenant::setting', [
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

        // Config::updateConfigs($itemKeys, 'tenant');

        return redirect(route('tenant.setting'));
    }
}
