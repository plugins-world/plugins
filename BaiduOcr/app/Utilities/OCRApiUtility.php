<?php

namespace Plugins\BaiduOcr\Utilities;

use Plugins\MarketManager\Utils\LaravelCache;

class OCRApiUtility
{
    public static function tokenGenerate()
    {
        $result = LaravelCache::remember('baidu_ocr_token', now()->addDays(28), function () {
            $method = 'POST';
            $action = OCRActionsUtility::ACTION_ACCESS_TOKEN_GENERATE;

            $ocr = OCRUtility::getOCR();
            $params = [
                'query' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $ocr->getApiKey(),
                    'client_secret' => $ocr->getSecretKey()
                ]
            ];

            $result = OCRUtility::request($method, $action, $params);

            return $result;
        });

        return $result;
    }

    public static function idCardVerify(array $data)
    {
        $tokenData = OCRApiUtility::tokenGenerate();
        $accessToken = $tokenData['access_token'];

        $method = 'POST';
        $action = OCRActionsUtility::ACTION_ID_CARD_VERIFY;

        $params = [
            'query' => ['access_token' => $accessToken],
            'form_params' => $data
        ];

        $result = OCRUtility::request($method, $action, $params);
        $wordsList = $result['words_result'];

        switch ($data['id_card_side']) {
            case 'front':
                $item['name'] = $wordsList['姓名']['words'];
                $item['nation'] = $wordsList['民族']['words'];
                $item['address'] = $wordsList['住址']['words'];
                $item['id_card_no'] = $wordsList['公民身份号码']['words'];
                $item['birthday'] = $wordsList['出生']['words'];

                $genderStr = $wordsList['性别']['words'];
                $item['gender_integer'] = match($genderStr) {
                    default => 0,
                    '男' => 1,
                    '女' => 2,
                };
                $item['gender_const'] = match($genderStr) {
                    default => 'UNKNOWN',
                    '男' => 'MALE',
                    '女' => 'FEMALE',
                };
                $item['gender_desc'] = $genderStr;
                break;
            case 'back':
                $item['issue_day_raw'] = $wordsList['签发日期']['words'];
                $item['expired_day_raw'] = $wordsList['失效日期']['words'];

                $item['issue_day_desc'] = date('Y-m-d', strtotime($item['issue_day_raw']));
                $item['expired_day_desc'] = date('Y-m-d', strtotime($item['expired_day_raw']));

                $item['issue_day_desc'] = date('Y-m-d 00:00:00', strtotime($item['issue_day_raw']));
                $item['expired_day_desc'] = date('Y-m-d 00:00:00', strtotime($item['expired_day_raw']));

                $item['signing_and_issuing_organization'] = $wordsList['签发机关']['words'];
                break;
            default:
                $item = [];
                break;
        }

        $result['data'] = $item;

        return $result;
    }

    public static function hkAndTaiwanExitEntryPermit(array $data)
    {
        $tokenData = OCRApiUtility::tokenGenerate();
        $accessToken = $tokenData['access_token'];

        $method = 'POST';
        $action = OCRActionsUtility::ACTION_HK_MACAU_TAIWAN_EXIT_ENTRY_PERMIT;

        $params = [
            'query' => ['access_token' => $accessToken],
            'form_params' => $data
        ];

        $result = OCRUtility::request($method, $action, $params);

        return $result;
    }
}
