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
        $smsDefaultGateway = Config::getValueByKey('sms_default_gateway');
        $qcloudConfig = Config::getValueByKey('qcloud');
        $aliyunConfig = Config::getValueByKey('aliyun');

        return view('EasySms::setting', [
            'sms_default_gateway' => $smsDefaultGateway,
            'qcloudConfig' => $qcloudConfig,
            'aliyunConfig' => $aliyunConfig
        ]);
    }

    public function saveSetting(Request $request)
    {
        $request->validate([
            'sms_default_gateway' => 'required|string',
            'qcloud' => 'required|array',
            'aliyun' => 'required|array'
        ]);

        $itemKeys = [
            'sms_default_gateway',
            'qcloud',
            'aliyun'
        ];

        ConfigUtility::updateConfigs($itemKeys, 'easy_sms');

        return redirect(route('easy-sms.setting'))->with('success', '保存成功');
    }
}
