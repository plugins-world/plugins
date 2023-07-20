<?php

namespace Plugins\PayCenter\Http\Controllers;

use Yansongda\Pay\Pay;
use Illuminate\Routing\Controller;
use ZhenMu\Support\Traits\ResponseTrait;
use Plugins\PayCenter\Utilities\PayUtility;

class PayCenterController extends Controller
{
    use ResponseTrait;

    public function wechatPay(string $payType)
    {
        request()->validate([
            'fskey' => ['required', 'string'],
            'cmdWord' => ['required', 'string'],
            'wordBody' => ['required', 'array'],
        ]);
        
        PayUtility::init('pay_center_wechatpay');

        $fskey = request('fskey');
        $cmdWord = request('cmdWord');
        $wordBody = request('wordBody');
        $resp = \FresnsCmdWord::plugin($fskey)->$cmdWord($wordBody);
        if ($resp->isErrorResponse()) {
            return $this->fail($resp->getMessage(), $resp->getCode());
        }

        // $order = [
        //     'out_trade_no' => time().'',
        //     'description' => 'subject-测试',
        //     'amount' => [
        //         'total' => 1,
        //         'currency' => 'CNY',
        //     ],
        //     'payer' => [
        //         'openid' => 'oNCK84ntV1IEDUhrdhgvUr4axmCI',
        //     ]
        // ];
        $order = $resp->getData();

        $wechat = Pay::wechat();
        if (! is_callable([$wechat, $payType])) {
            return $this->fail("支付类型 {$payType} 不存在");
        }

        $result = $wechat->$payType($order);

        return $this->success($result);
    }
}
