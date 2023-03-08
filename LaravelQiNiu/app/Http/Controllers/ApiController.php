<?php

namespace Plugins\LaravelQiNiu\Http\Controllers;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function getToken()
    {        
        $cmdWordResp = \FresnsCmdWord::plugin('LaravelQiNiu')->getToken(\request()->all());

        return $this->success($cmdWordResp->getData());
    }

    public function upload()
    {
        $cmdWordResp = \FresnsCmdWord::plugin('LaravelQiNiu')->upload([
            'file' => \request('file'),
            'path' => \request('path'),
        ]);

        return $this->success($cmdWordResp->getData());
    }
}
