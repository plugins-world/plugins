<?php

namespace Plugins\SystemAuthorization\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MouYong\LaravelConfig\Models\Config;
use ZhenMu\Support\Traits\ResponseTrait;

class SystemAuthorizationController extends Controller
{
    use ResponseTrait;

    public function getRsaKeyByType()
    {
        \request()->validate([
            'type' => 'required|in:rsa_public_key',
        ]);

        $rsaKeyType = \request('type');

        $itemValue = Config::getValueByKey($rsaKeyType);

        return $this->success([
            'contents' => $itemValue,
        ]);
    }
}