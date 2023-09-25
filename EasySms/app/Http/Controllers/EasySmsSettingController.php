<?php

namespace Plugins\EasySms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Plugins\LaravelConfig\Models\Config;
use Plugins\LaravelConfig\Utilities\ConfigUtility;

class EasySmsSettingController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('EasySms::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView(Request $request)
    {
        config(['session.same_site' => 'none']);
        config(['session.secure' => uniqid()]);

        // code
        $itemKeys = [
            'sms_default_gateway',
            'qcloud',
        ];

        $configs = Config::getValueByKeys($itemKeys);

        return view('EasySms::setting', [
            'configs' => $configs,
        ]);
    }

    public function saveSetting(Request $request)
    {
        $request->validate([
            'sms_default_gateway' => 'required|string',
            'qcloud' => 'required|array',
        ]);

        $itemKeys = [
            'sms_default_gateway',
            'qcloud',
        ];

        ConfigUtility::updateConfigs($itemKeys, 'easy_sms');

        return redirect(route('easy-sms.setting'))->with('success', '保存成功');
    }
}
