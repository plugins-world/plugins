<?php

namespace Plugins\EasyMap\Services;

use Plugins\EasyMap\Utilities\AMapApiUtility;
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

    public function getGeoCodeGeoInfo(array $wordBody)
    {
        $address = $wordBody['address'] ?? '';

        try {
            $resp = AMapApiUtility::getGeoCodeGeoInfo($address);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function getGeoCodeRegeoInfo(array $wordBody)
    {
        $longitude = $wordBody['longitude'] ?? '';
        $latitude = $wordBody['latitude'] ?? '';
        $user_address = $wordBody['user_address'] ?? '';

        try {
            $resp = AMapApiUtility::getGeoCodeRegeoInfo($longitude, $latitude, $user_address);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }
}
