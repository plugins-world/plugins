<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\PayCenter\Services;

use Yansongda\Pay\Pay;
use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;
use Plugins\PayCenter\Utilities\PayUtility;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function wechatPay(array $wordBody)
    {
        $payType = $wordBody['payType'];

        $fskey = $wordBody['fskey'];
        $cmdWord = $wordBody['cmdWord'];
        $newWordBody = $wordBody['wordBody'];

        $resp = \FresnsCmdWord::plugin($fskey)->$cmdWord($newWordBody);
        if ($resp->isErrorResponse()) {
            return $this->failure($resp->getCode(), $resp->getMessage(), $resp->getData());
        }
        $order = $resp->getData();

        $config = PayUtility::init('pay_center_wechatpay');
        $wechat = Pay::wechat($config);
        if (!is_callable([$wechat, $payType])) {
            return $this->failure(400, "支付类型 {$payType} 不存在");
        }

        $result = $wechat->$payType($order);

        return $this->success($result);
    }

    public function callbackParse(array $wordBody)
    {
        $type = $wordBody['type'];

        $result = PayUtility::callback($type);

        return $this->success($result);
    }

    public function callbackResponse(array $wordBody)
    {
        $type = $wordBody['type'];

        $result = PayUtility::success($type);

        return $this->success($result);
    }
}
