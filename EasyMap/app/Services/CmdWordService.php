<?php

namespace Plugins\EasyMap\Services;

use Plugins\EasyMap\Utilities\MapUtility;
use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function request(array $wordBody)
    {
        $action = $wordBody['action'];
        $method = $wordBody['method'];
        $params = $wordBody['params'];

        try {
            $resp = MapUtility::request($method, $action, $params);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }
}
