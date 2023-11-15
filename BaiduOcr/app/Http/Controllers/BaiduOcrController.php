<?php

namespace Plugins\BaiduOcr\Http\Controllers;

use Illuminate\Routing\Controller;
use ZhenMu\Support\Traits\ResponseTrait;

class BaiduOcrController extends Controller
{
    use ResponseTrait;

    public function idCardVerify()
    {
        \request()->validate([
            'id_card_side' => ['required', 'string'],
            'url' => ['required', 'string'],
            'detect_risk' => ['nullable', 'string'],
            'detect_quality' => ['nullable', 'string'],
            'detect_photo' => ['nullable', 'string'],
            'detect_card' => ['nullable', 'string']
        ]);

        $resp = \FresnsCmdWord::plugin('BaiduOcr')->idCardVerify([
            'id_card_side' => \request('id_card_side'),
            'url' => \request('url'),
            'detect_risk' => \request('detect_risk') ?? 'false',
            'detect_quality' => \request('detect_quality') ?? 'false',
            'detect_photo' => \request('detect_photo') ?? 'false',
            'detect_card' => \request('detect_card') ?? 'false'
        ]);
        $idCardData = $resp->getData();

        return $this->success($idCardData);
    }
}
