<?php

namespace Plugins\LaravelLocalStorage\Http\Controllers;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function upload()
    {
        $cmdWordResp = \FresnsCmdWord::plugin('LaravelLocalStorage')->upload([
            'file' => \request('file'),
            'path' => \request('path'),
        ]);

        return $this->success($cmdWordResp->getData());
    }
}
