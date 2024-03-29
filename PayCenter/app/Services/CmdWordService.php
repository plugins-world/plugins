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

    public function handlePayAction(array $wordBody)
    {
        $payPlatform = $wordBody['payPlatform'];
        $payAction = $wordBody['payAction'];
        $initConfigKey = $wordBody['initConfigKey'];

		// 初始化后支付配置
        $config = PayUtility::init($initConfigKey);

		// 获取订单信息
        $rpc = $wordBody['rpc'];
        $fskey = $rpc['fskey'];
        $cmdWord = $rpc['cmdWord'];
        $newWordBody = $rpc['wordBody'];
        $resp = \FresnsCmdWord::plugin($fskey)->$cmdWord($newWordBody);
        if ($resp->isErrorResponse()) {
            return $this->failure($resp->getCode(), $resp->getMessage(), $resp->getData());
        }
        $order = $resp->getData();

		// 获取支付平台实例
		/** @var \Yansongda\Pay\Provider\Wechat|\Yansongda\Pay\Provider\Alipay|\Yansongda\Pay\Provider\Unipay $platform */
        $platform = Pay::$payPlatform($config);
        if (!is_callable([$platform, $payAction])) {
            return $this->failure(400, "{$payPlatform}::{$payAction} 不存在");
        }

		// 发起支付申请
		try {
			$result = $platform->{$payAction}($order);
		} catch (\Throwable $e) {
			/** @var \Yansongda\Artful\Exception\InvalidResponseException $e */
			$message = $e->getMessage();

			$data = [];
			if ($e instanceof \Yansongda\Artful\Exception\InvalidResponseException) {
				$data = $e->response->toArray();

				$message = sprintf('%s, code: %s, message: %s', $message, $data['code'], $data['message']);
				return $this->failure(400, $message);
			}

            return $this->failure(400, $message);
		}

        info('handle result', $result->toArray());
        if (!empty($result->code)) {
            return $this->failure(400, "code: {$result->code}, message: {$result->message}");
        }

        return $this->success($result);
    }

    public function handlePayCallbackParse(array $wordBody)
    {
		$payPlatform = $wordBody['payPlatform'];
        $initConfigKey = $wordBody['initConfigKey'];

        $result = PayUtility::callback($payPlatform, $initConfigKey);

        return $this->success($result);
    }

    public function handlePayCallbackResponse(array $wordBody)
    {
		$payPlatform = $wordBody['payPlatform'];
        $initConfigKey = $wordBody['initConfigKey'];

        $result = PayUtility::success($payPlatform, $initConfigKey);

        return $this->success($result);
    }
}
