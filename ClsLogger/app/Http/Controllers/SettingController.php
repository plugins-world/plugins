<?php

namespace Plugins\ClsLogger\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('ClsLogger::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView(Request $request)
    {
        config(['session.same_site' => 'none']);
        config(['session.secure' => uniqid()]);

        // code
        $itemKeys = [
            // 'item_key1',
            // 'item_key2',
        ];

        // $configs = Config::whereIn('item_key', $itemKeys)->where('item_tag', 'cls_logger')->get();
        $configs = [];

        return view('ClsLogger::setting', [
            'configs' => $configs,
        ]);
    }

    public function saveSetting(Request $request)
    {
        $request->validate([
            // 'item_key1' => 'required|url',
            // 'item_key2' => 'nullable|url',
        ]);

        $itemKeys = [
            // 'item_key1',
            // 'item_key2',
        ];

        // code
        // Config updateConfigs with $itemKeys and 'cls_logger'

        return redirect(route('cls-logger.setting'));
    }
}
