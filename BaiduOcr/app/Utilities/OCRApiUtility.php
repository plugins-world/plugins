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
        $wordsList =  array_column($result['words_result'], 'words');

        list($name, $nationality, $address, $id_card, $birthday, $gender) = $wordsList;
        $result['name'] = $name;
        $result['nationality'] = $nationality;
        $result['address'] = $address;
        $result['id_card'] = $id_card;
        $result['birthday'] = $birthday;
        $result['gender'] = $gender;

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
