<?php

namespace Plugins\WechatLogin\Http\Controllers;

use Illuminate\Routing\Controller;
use Plugins\WechatLogin\Models\AccountConnect;
use Plugins\WechatLogin\Utilities\WechatUtility;

class WechatLoginController extends Controller
{
    public function login()
    {
        \request()->validate([
            'code' => ['required', 'string'],
        ]);
        
        $code = \request('code');
        $app = WechatUtility::getApp(WechatUtility::TYPE_MINI_PROGRAM);
        $utils = $app->getUtils();
        $response = $utils->codeToSession($code);

        // $response = [
        //     "session_key" => "xxx"
        //     "openid" => "xxxxx"
        // ]

        $user = AccountConnect::where('openid', $response['openid'])->first();

        return $this->success([
            'token' => $user->token,
        ]);
    }
}
