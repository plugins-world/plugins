<?php

namespace Plugins\SanctumAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Plugins\SanctumAuth\Models\SanctumAuth;

class SanctumAuthController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('SanctumAuth::index', [
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

        // $configs = Config::whereIn('item_key', $itemKeys)->where('item_tag', 'sanctum_auth')->get();
        $configs = [];

        return view('SanctumAuth::setting', [
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
        // Config updateConfigs with $itemKeys and 'sanctum_auth'

        return redirect(route('sanctum-auth.setting'));
    }
}
