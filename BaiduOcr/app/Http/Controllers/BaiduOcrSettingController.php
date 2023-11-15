<?php

namespace Plugins\BaiduOcr\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Plugins\LaravelConfig\Models\Config;
use Plugins\LaravelConfig\Utilities\ConfigUtility;

class BaiduOcrSettingController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('BaiduOcr::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView(Request $request)
    {
        config(['session.same_site' => 'none']);
        config(['session.secure' => uniqid()]);

        // code
        $ocrConfig = Config::getValueByKey('ocr_config', 'baidu_ocr');

        return view('BaiduOcr::setting', [
            'config' => $ocrConfig,
        ]);
    }

    public function saveSetting(Request $request)
    {
        $request->validate([
            'ocr_config' => 'required|array',
        ]);

        $itemKeys = [
            'ocr_config'
        ];

        ConfigUtility::updateConfigs($itemKeys, 'baidu_ocr');

        return redirect(route('baidu-ocr.setting'));
    }
}
