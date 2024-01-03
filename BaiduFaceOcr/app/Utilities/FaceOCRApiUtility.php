<?php

namespace Plugins\BaiduFaceOcr\Utilities;

use Plugins\MarketManager\Utils\LaravelCache;

class FaceOCRApiUtility
{
    public static function tokenGenerate()
    {
        $result = LaravelCache::remember('baidu_face_ocr_token', now()->addDays(28), function () {
            $method = 'POST';
            $action = FaceOCRActionsUtility::ACTION_ACCESS_TOKEN_GENERATE;

            $ocr = FaceOCRUtility::getOCR();
            $params = [
                'query' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $ocr->getApiKey(),
                    'client_secret' => $ocr->getSecretKey(),
                ]
            ];

            $result = FaceOCRUtility::request($method, $action, $params);

            return $result;
        });

        return $result;
    }

    public static function faceVerifyTokenGenerate(array $data)
    {
        $tokenData = FaceOCRApiUtility::tokenGenerate();
        $accessToken = $tokenData['access_token'];

        $method = 'POST';
        $action = FaceOCRActionsUtility::ACTION_FACE_VERIFY_TOKEN_GENERATE;

        $params = [
            'query' => ['access_token' => $accessToken],
            'json' => $data,
        ];
        
        $result = FaceOCRUtility::request($method, $action, $params);

        $result['verify_token'] = $result['result']['verify_token'] ?? null;

        return $result;
    }

    public static function faceIdCardSubmit(array $data)
    {
        $tokenData = FaceOCRApiUtility::tokenGenerate();
        $accessToken = $tokenData['access_token'];

        $method = 'POST';
        $action = FaceOCRActionsUtility::ACTION_FACE_ID_CARD_SUBMIT;

        $params = [
            'query' => ['access_token' => $accessToken],
            'json' => $data,
        ];

        $result = FaceOCRUtility::request($method, $action, $params);

        return $result;
    }

    public static function faceUploadMatchImage(array $data)
    {
        $tokenData = FaceOCRApiUtility::tokenGenerate();
        $accessToken = $tokenData['access_token'];

        $method = 'POST';
        $action = FaceOCRActionsUtility::ACTION_FACE_UPLOAD_MATCH_IMAGE;

        $params = [
            'query' => ['access_token' => $accessToken],
            'json' => $data,
        ];

        $result = FaceOCRUtility::request($method, $action, $params);

        return $result;
    }

    public static function faceResultSimple(array $data)
    {
        $tokenData = FaceOCRApiUtility::tokenGenerate();
        $accessToken = $tokenData['access_token'];

        $method = 'POST';
        $action = FaceOCRActionsUtility::ACTION_FACE_RESULT_SIMPLE;

        $params = [
            'query' => ['access_token' => $accessToken],
            'json' => $data,
        ];

        $result = FaceOCRUtility::request($method, $action, $params);

        return $result;
    }

    public static function faceResultDetail(array $data)
    {
        $tokenData = FaceOCRApiUtility::tokenGenerate();
        $accessToken = $tokenData['access_token'];

        $method = 'POST';
        $action = FaceOCRActionsUtility::ACTION_FACE_RESULT_DETAIL;

        $params = [
            'query' => ['access_token' => $accessToken],
            'json' => $data,
        ];

        $result = FaceOCRUtility::request($method, $action, $params);

        return $result;
    }

    public static function faceResultStat(array $data)
    {
        $tokenData = FaceOCRApiUtility::tokenGenerate();
        $accessToken = $tokenData['access_token'];

        $method = 'POST';
        $action = FaceOCRActionsUtility::ACTION_FACE_RESULT_STAT;

        $params = [
            'query' => ['access_token' => $accessToken],
            'json' => $data,
        ];

        $result = FaceOCRUtility::request($method, $action, $params);

        return $result;
    }

    public static function faceResultMediaQuery(array $data)
    {
        $tokenData = FaceOCRApiUtility::tokenGenerate();
        $accessToken = $tokenData['access_token'];

        $method = 'POST';
        $action = FaceOCRActionsUtility::ACTION_FACE_RESULT_MEDIA_QUERY;

        $params = [
            'query' => ['access_token' => $accessToken],
            'json' => $data,
        ];

        $result = FaceOCRUtility::request($method, $action, $params);

        return $result;
    }

    public static function faceResultGetAll(array $data)
    {
        $tokenData = FaceOCRApiUtility::tokenGenerate();
        $accessToken = $tokenData['access_token'];

        $method = 'POST';
        $action = FaceOCRActionsUtility::ACTION_FACE_RESULT_GET_ALL;

        $params = [
            'query' => ['access_token' => $accessToken],
            'json' => $data,
        ];

        $result = FaceOCRUtility::request($method, $action, $params);

        return $result;
    }
}
