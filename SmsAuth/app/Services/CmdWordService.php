<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\SmsAuth\Services;

use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function sendCode(array $wordBody)
    {
        // 获取发送短信需要的参数信息
        $rpc = $wordBody['rpc'];
        $fskey = $rpc['fskey'];
        $cmdWord = $rpc['cmdWord'];
        $newWordBody = $rpc['wordBody'];
        $smsActionResp = \FresnsCmdWord::plugin($fskey)->$cmdWord($newWordBody);
        if ($smsActionResp->isErrorResponse()) {
            return $this->failure($smsActionResp->getCode(), $smsActionResp->getMessage(), $smsActionResp->getData());
        }
        $result = $smsActionResp->getData();

        // 发送短信，依赖 EasySms 插件
        $sendSmsResp = \FresnsCmdWord::plugin('EasySms')->send([
            'to' => $result['to'],
            'params' => $result['params'],
        ]);
        if ($sendSmsResp->isErrorResponse()) {
            return $this->failure($sendSmsResp->getCode(), $sendSmsResp->getMessage(), $sendSmsResp->getData());
        }
        $result = $sendSmsResp->getData();

        return $this->success($result);
    }
}
