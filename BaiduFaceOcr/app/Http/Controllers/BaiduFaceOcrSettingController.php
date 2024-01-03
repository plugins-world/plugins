<?php

namespace Plugins\BaiduFaceOcr\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Plugins\LaravelConfig\Models\Config;
use Plugins\LaravelConfig\Utilities\ConfigUtility;

class BaiduFaceOcrSettingController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('BaiduFaceOcr::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView(Request $request)
    {
        config(['session.same_site' => 'none']);
        config(['session.secure' => uniqid()]);

        // code
        $faceOcrConfig = Config::getValueByKey('face_ocr_config', 'baidu_face_ocr');
        $faceOcrPlan = Config::getValueByKey('face_ocr_plan', 'baidu_face_ocr');

        return view('BaiduFaceOcr::setting', [
            'config' => $faceOcrConfig,
            'plan' => $faceOcrPlan
        ]);
    }

    public function saveSetting(Request $request)
    {
        $request->validate([
            'face_ocr_config' => 'required|array',
            'face_ocr_plan' => 'required|array',
        ]);

        $itemKeys = [
            'face_ocr_config',
            'face_ocr_plan',
        ];

        ConfigUtility::updateConfigs($itemKeys, 'baidu_face_ocr');

        return redirect(route('baidu-face-ocr.setting'));
    }
}
