<?php

namespace Plugins\BaiduFaceOcr\Services;

use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;
use Plugins\BaiduFaceOcr\Utilities\FaceOCRApiUtility;
use Plugins\BaiduFaceOcr\Utilities\FaceOCRUtility;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function request(array $wordBody)
    {
        $action = $wordBody['action'];
        $method = $wordBody['method'];
        $params = $wordBody['params'];

        try {
            $resp = FaceOCRUtility::request($method, $action, $params);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function tokenGenerate()
    {
        try {
            $resp = FaceOCRApiUtility::tokenGenerate();
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function faceVerifyTokenGenerate(array $wordBody)
    {
        $data = [
            'plan_id' => $wordBody['plan_id'] ?? ''
        ];

        try {
            $resp = FaceOCRApiUtility::faceVerifyTokenGenerate($data);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function faceIdCardSubmit(array $wordBody)
    {
        $data = [
            'verify_token' => $wordBody['verify_token'] ?? '',
            'id_name' => $wordBody['id_name'] ?? '',
            'id_no' => $wordBody['id_no'] ?? '',
            'certificate_type' => $wordBody['certificate_type'] ?? 0
        ];

        try {
            $resp = FaceOCRApiUtility::faceIdCardSubmit($data);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function faceUploadMatchImage(array $wordBody)
    {
        $data = [
            'verify_token' => $wordBody['verify_token'] ?? '',
            'image' => $wordBody['image'] ?? ''
        ];

        try {
            $resp = FaceOCRApiUtility::faceUploadMatchImage($data);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function faceResultSimple(array $wordBody)
    {
        $data = [
            'verify_token' => $wordBody['verify_token'] ?? ''
        ];

        try {
            $resp = FaceOCRApiUtility::faceResultSimple($data);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function faceResultDetail(array $wordBody)
    {
        $data = [
            'verify_token' => $wordBody['verify_token'] ?? ''
        ];

        try {
            $resp = FaceOCRApiUtility::faceResultDetail($data);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function faceResultStat(array $wordBody)
    {
        $data = [
            'verify_token' => $wordBody['verify_token'] ?? ''
        ];

        try {
            $resp = FaceOCRApiUtility::faceResultStat($data);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function faceResultMediaQuery(array $wordBody)
    {
        $data = [
            'verify_token' => $wordBody['verify_token'] ?? ''
        ];

        try {
            $resp = FaceOCRApiUtility::faceResultMediaQuery($data);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    public function faceResultGetAll(array $wordBody)
    {
        $data = [
            'verify_token' => $wordBody['verify_token'] ?? ''
        ];

        try {
            $resp = FaceOCRApiUtility::faceResultGetAll($data);
        } catch (\Throwable $e) {
            return $this->failure($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }
}
