<?php

namespace Plugins\BaiduFaceOcr\Utilities;

class FaceOCRActionsUtility
{
    /**
     * 生成access_token API
     * 文档：https://ai.baidu.com/ai-doc/REFERENCE/Ck3dwjhhu#2-%E8%8E%B7%E5%8F%96-access_token
     */
    const ACTION_ACCESS_TOKEN_GENERATE = '/oauth/2.0/token';

    /**
     * 获取verify_token API
     * 文档：https://ai.baidu.com/ai-doc/FACE/Xkxie8338#1%E8%8E%B7%E5%8F%96verify_token%E6%8E%A5%E5%8F%A3
     */
    const ACTION_FACE_VERIFY_TOKEN_GENERATE = '/rpc/2.0/brain/solution/faceprint/verifyToken/generate';

    /**
     * 指定用户上报 API
     * 文档：https://ai.baidu.com/ai-doc/FACE/Xkxie8338#2%E6%8C%87%E5%AE%9A%E7%94%A8%E6%88%B7%E4%BF%A1%E6%81%AF%E4%B8%8A%E6%8A%A5%E6%8E%A5%E5%8F%A3
     */
    const ACTION_FACE_ID_CARD_SUBMIT = '/rpc/2.0/brain/solution/faceprint/idcard/submit';

    /**
     * 对比图片上传 API
     * 文档：https://ai.baidu.com/ai-doc/FACE/Xkxie8338#3%E5%AF%B9%E6%AF%94%E5%9B%BE%E7%89%87%E4%B8%8A%E4%BC%A0%E6%8E%A5%E5%8F%A3
     */
    const ACTION_FACE_UPLOAD_MATCH_IMAGE = '/rpc/2.0/brain/solution/faceprint/uploadMatchImage';

    /**
     * 获取认证人脸 API
     * 文档： https://ai.baidu.com/ai-doc/FACE/Xkxie8338#1%E8%8E%B7%E5%8F%96%E8%AE%A4%E8%AF%81%E4%BA%BA%E8%84%B8%E6%8E%A5%E5%8F%A3
     */
    const ACTION_FACE_RESULT_SIMPLE = '/rpc/2.0/brain/solution/faceprint/result/simple';

    /**
     * 查询认证结果 API
     * 文档： https://ai.baidu.com/ai-doc/FACE/Xkxie8338#2%E6%9F%A5%E8%AF%A2%E8%AE%A4%E8%AF%81%E7%BB%93%E6%9E%9C%E6%8E%A5%E5%8F%A3
     */
    const ACTION_FACE_RESULT_DETAIL = '/rpc/2.0/brain/solution/faceprint/result/detail';

    /**
     * 查询统计结果 API
     * 文档： https://ai.baidu.com/ai-doc/FACE/Xkxie8338#3%E6%9F%A5%E8%AF%A2%E7%BB%9F%E8%AE%A1%E7%BB%93%E6%9E%9C
     */
    const ACTION_FACE_RESULT_STAT = '/rpc/2.0/brain/solution/faceprint/result/stat';

    /**
     * 实时方案视频获取 API
     * 文档： https://ai.baidu.com/ai-doc/FACE/Xkxie8338#4%E5%AE%9E%E6%97%B6%E6%96%B9%E6%A1%88%E8%A7%86%E9%A2%91%E8%8E%B7%E5%8F%96
     */
    const ACTION_FACE_RESULT_MEDIA_QUERY = '/rpc/2.0/brain/solution/faceprint/result/media/query';

    /**
     * 核验及计费信息获取 API
     * 文档： https://ai.baidu.com/ai-doc/FACE/Xkxie8338#5%E6%A0%B8%E9%AA%8C%E5%8F%8A%E8%AE%A1%E8%B4%B9%E4%BF%A1%E6%81%AF%E8%8E%B7%E5%8F%96
     */
    const ACTION_FACE_RESULT_GET_ALL = '/rpc/2.0/brain/solution/faceprint/result/getall';
}
