<?php

namespace Plugins\SmsAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SmsAuthSettingController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('SmsAuth::index', [
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

        // $configs = Config::whereIn('item_key', $itemKeys)->where('item_tag', 'sms_auth')->get();
        $configs = [];

        return view('SmsAuth::setting', [
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
        // Config updateConfigs with $itemKeys and 'sms_auth'

        return redirect(route('sms-auth.setting'));
    }
}