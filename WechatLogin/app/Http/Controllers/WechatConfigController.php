<?php

namespace Plugins\WechatLogin\Http\Controllers;

use Illuminate\Routing\Controller;
use ZhenMu\Support\Traits\ResponseTrait;
use Plugins\WechatLogin\Utilities\WechatUtility;

class WechatConfigController extends Controller
{
    use ResponseTrait;

    public function getJssdkConfig()
    {
        request()->validate([
            'app_id' => ['required', 'string'],
            'url' => ['nullable', 'string'],
            'jsApiList' => ['nullable', 'array'],
            'openTagList' => ['nullable', 'array'],
            'debug' => ['nullable', 'boolean:0,1'],
        ]);

        $appId = request('app_id');

        $url = request('url', request()->getHttpHost());
        $jsApiList = request('jsApiList', []);
        $openTagList = request('openTagList', []);
        $debug = request()->boolean('debug', false);

        $app = WechatUtility::getApp(WechatUtility::TYPE_OFFICIAL_ACCOUNT, $appId);
        if (!$app) {
            return $this->fail('请先配置 app_id 等相关信息');
        }

        /** @var \EasyWeChat\OfficialAccount\Utils */
        $utils = $app->getUtils();

        $config = $utils->buildJsSdkConfig(
            $url,
            $jsApiList,
            $openTagList,
            $debug
        );

        return $this->success($config);
    }
}
