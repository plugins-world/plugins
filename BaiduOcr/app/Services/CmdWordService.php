<?php

namespace Plugins\BaiduOcr\Services;

use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;
use Plugins\BaiduOcr\Utilities\OCRApiUtility;
use Plugins\BaiduOcr\Utilities\OCRUtility;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function request(array $wordBody)
    {
        $action = $wordBody['action'];
        $method = $wordBody['method'];
        $params = $wordBody['params'];

        try {
            $resp = OCRUtility::request($method, $action, $params);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function tokenGenerate()
    {
        try {
            $resp = OCRApiUtility::tokenGenerate();
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function idCardVerify(array $wordBody)
    {
        $data = [
            'id_card_side' => $wordBody['id_card_side'] ?? '',
            'detect_risk' => $wordBody['detect_risk'] ?? 'false',
            'detect_quality' => $wordBody['detect_quality'] ?? 'false',
            'detect_photo' => $wordBody['detect_photo'] ?? 'false',
            'detect_card' => $wordBody['detect_card'] ?? 'false',
            'detect_direction' => $wordBody['detect_direction'] ?? 'false',
        ];
        if (isset($wordBody['image'])) {
            $data['image'] = $wordBody['image'];
        }
        if (isset($wordBody['url'])) {
            $data['url'] = $wordBody['url'];
        }

        try {
            $resp = OCRApiUtility::idCardVerify($data);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function hkAndTaiwanExitEntryPermit(array $wordBody)
    {
        $data = [
            'exitentrypermit_type' => $wordBody['exitentrypermit_type'] ?? '',
            'probability' => $wordBody['probability'] ?? 'false',
            'location' => $wordBody['location'] ?? 'false',
        ];
        if (isset($wordBody['image'])) {
            $data['image'] = $wordBody['image'];
        }
        if (isset($wordBody['url'])) {
            $data['url'] = $wordBody['url'];
        }
        if (isset($wordBody['pdf_file'])) {
            $data['pdf_file'] = $wordBody['pdf_file'];
            $data['pdf_file_num'] = $wordBody['pdf_file_num'] ?? 1;
        }

        try {
            $resp = OCRApiUtility::hkAndTaiwanExitEntryPermit($data);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }
}
