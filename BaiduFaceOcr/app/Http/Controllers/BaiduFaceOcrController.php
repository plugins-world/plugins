<?php

namespace Plugins\BaiduFaceOcr\Http\Controllers;

use Illuminate\Routing\Controller;
use ZhenMu\Support\Traits\ResponseTrait;
use Plugins\LaravelConfig\Models\Config;

class BaiduFaceOcrController extends Controller
{
    use ResponseTrait;

    public function faceVerify()
    {
        \request()->validate([
            'account_id' => ['required', 'integer'],
            'success_url' => ['required', 'url'],
            'failed_url' => ['required', 'url'],

            'fskey' => ['required', 'string'],
            'cmdWord' => ['nullable', 'string'],
        ]);

        $accountId = \request('account_id');
        $successUrl = \request('success_url');
        $failedUrl = \request('failed_url');

        $fskey = \request('fskey');
        $cmdWord = \request('cmdWord', 'getAccountIdCardInfo');
        $rpcParams = ['account_id' => $accountId];

        // TODO :: 获取verifyToken
        $faceOcrPlan = Config::getValueByKey('face_ocr_plan', 'baidu_face_ocr');

        $resp = \FresnsCmdWord::plugin('BaiduFaceOcr')->faceVerifyTokenGenerate([
            'plan_id' => $faceOcrPlan['id'] ?? null,
        ]);
        /** @var \Fresns\CmdWordManager\CmdWordResponse */
        if ($resp->isErrorResponse()) {
            return $this->fail($resp->getMessage());
        }
        
        $data = $resp->getData();
        $verifyToken = $data['verify_token'] ?? null;

        // TODO :: RPC调用 业务插件 获取账户身份证信息
        $wordBody = [
            'rpc' => [
                'fskey' => $fskey,
                'cmdWord' => $cmdWord,
                'wordBody' => [
                    'data' => $rpcParams,
                ],
            ],
        ];

        $rpc = $wordBody['rpc'];
        $fskey = $rpc['fskey']; //getAccountIdCardInfo
        $cmdWord = $rpc['cmdWord'];
        $newWordBody = $rpc['wordBody'];
        $accountActionResp = \FresnsCmdWord::plugin($fskey)->$cmdWord($newWordBody);
        if ($accountActionResp->isErrorResponse() || $resp->getMessage() !== 'success') {
            return $this->fail($accountActionResp->getMessage());
        }

        $result = $accountActionResp->getData();
        $realName = $result['real_name'];
        $idCard = $result['id_card'];

        // TODO :: 用户信息上报
        $idCardSubmitResp = \FresnsCmdWord::plugin('BaiduFaceOcr')->faceIdCardSubmit([
            'verify_token' => $verifyToken,
            'id_name' => $realName,
            'id_no' => $idCard,
        ]);
        if ($idCardSubmitResp->isErrorResponse()) {
            return $this->fail($idCardSubmitResp->getMessage());
        }

        // TODO :: H5人脸识别URL
        $url = sprintf("https://brain.baidu.com/face/print?token=%s&successUrl=%s&failedUrl=%s", $verifyToken, $successUrl, $failedUrl);

        return $this->success([
            'verify_url' => $url
        ]);
    }
}
