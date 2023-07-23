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
            return $this->failure(400, "订单 {$payType} 支付类型不存在");
        }

        $result = $wechat->$payType($order);

        return $this->success($result);
    }

    public function handle(array $wordBody)
    {
        $payPlatform = $wordBody['payPlatform'];
        $orderAction = $wordBody['orderAction'];
        $initConfigKey = $wordBody['init_config_key'];
        $config = PayUtility::init($initConfigKey);

        $rpc = $wordBody['rpc'];
        $fskey = $rpc['fskey'];
        $cmdWord = $rpc['cmdWord'];
        $newWordBody = $rpc['wordBody'];
        $resp = \FresnsCmdWord::plugin($fskey)->$cmdWord($newWordBody);
        if ($resp->isErrorResponse()) {
            return $this->failure($resp->getCode(), $resp->getMessage(), $resp->getData());
        }
        $order = $resp->getData();

        $platform = Pay::$payPlatform($config);
        if (!is_callable([$platform, $orderAction])) {
            return $this->failure(400, "订单 {$orderAction} 操作不存在");
        }
        $result = $platform->{$orderAction}($order);

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
