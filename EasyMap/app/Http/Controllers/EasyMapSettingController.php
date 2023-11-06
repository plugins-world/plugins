<?php

namespace Plugins\EasyMap\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Plugins\LaravelConfig\Models\Config;
use Plugins\LaravelConfig\Utilities\ConfigUtility;

class EasyMapSettingController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('EasyMap::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView(Request $request)
    {
        config(['session.same_site' => 'none']);
        config(['session.secure' => uniqid()]);

        // code
        $map_default_platform = Config::getValueByKey('map_default_platform');
        $amapConfig = Config::getValueByKey('amap');

        return view('EasyMap::setting', [
            'map_default_platform' => $map_default_platform,
            'amapConfig' => $amapConfig,
        ]);
    }

    public function saveSetting(Request $request)
    {
        $request->validate([
            'map_default_platform' => 'required|string',
            'amap' => 'required|array',
        ]);

        $itemKeys = [
            'map_default_platform',
            'amap',
        ];

        ConfigUtility::updateConfigs($itemKeys, 'easy_map');

        return redirect(route('easy-map.setting'));
    }
}
