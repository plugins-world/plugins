<?php

namespace Plugins\BaiduOcr\Utilities;

class OCRActionsUtility
{
    /**
     * 生成access_token API
     * 文档：https://ai.baidu.com/ai-doc/REFERENCE/Ck3dwjhhu#2-%E8%8E%B7%E5%8F%96-access_token
     */
    const ACTION_ACCESS_TOKEN_GENERATE = '/oauth/2.0/token';

    /**
     * 身份证识别 API
     * 文档：https://ai.baidu.com/ai-doc/OCR/rk3h7xzck
     */
    const ACTION_ID_CARD_VERIFY = '/rest/2.0/ocr/v1/idcard';

    /**
     * 港澳台通行证识别 API
     * 文档：https://ai.baidu.com/ai-doc/OCR/Tlg6859ns
     */
    const ACTION_HK_MACAU_TAIWAN_EXIT_ENTRY_PERMIT = '/rest/2.0/ocr/v1/hk_macau_taiwan_exitentrypermit';
}
