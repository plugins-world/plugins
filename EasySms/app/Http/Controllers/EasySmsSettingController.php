<?php

namespace Plugins\EasySms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Plugins\LaravelConfig\Models\Config;
use Plugins\EasySms\Utilities\SmsUtility;
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
        // $to = '12345678901';
        // $params = [
        //     'template' => '1686043',
        //     'data' => [
        //         'sign_name' => '插件世界', // 可以通过设置项进行配置
        //         '{1}' => '1234',
        //     ],
        // ];
        
        // $resp = \FresnsCmdWord::plugin('EasySms')->send([
        //     'to' => $to,
        //     'params' => $params,
        // ]);
        
        // dd($resp);
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
