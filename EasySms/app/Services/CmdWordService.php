<?php

namespace Plugins\EasySms\Services;

use Plugins\EasySms\Utilities\SmsUtility;
use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function send(array $wordBody)
    {
        $to = $wordBody['to'];
        $params = $wordBody['params'];

        $resp = SmsUtility::send($to, $params);

        return $this->success($resp);
    }
}
