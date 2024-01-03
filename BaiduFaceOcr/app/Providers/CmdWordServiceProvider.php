<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\BaiduFaceOcr\Providers;

use Fresns\CmdWordManager\Contracts\CmdWordProviderContract;
use Fresns\CmdWordManager\Traits\CmdWordProviderTrait;
use Illuminate\Support\ServiceProvider;
use Plugins\BaiduFaceOcr\Services\CmdWordService;

class CmdWordServiceProvider extends ServiceProvider implements CmdWordProviderContract
{
    use CmdWordProviderTrait;

    protected $fsKeyName = 'BaiduFaceOcr';

    /**
     * Command words map
     *
     * @var array[]
     */
    protected $cmdWordsMap = [
        // ['word' => AWordService::CMD_TEST, 'provider' => [AWordService::class, 'handleTest']],
        // ['word' => BWordService::CMD_STATIC_TEST, 'provider' => [BWordService::class, 'handleStaticTest']],
        // ['word' => TestModel::CMD_MODEL_TEST, 'provider' => [TestModel::class, 'handleModelTest']],
        // ['word' => 'cmdWord', 'provider' => [CmdWordService::class, 'cmdWord']],
        ['word' => 'faceVerifyTokenGenerate', 'provider' => [CmdWordService::class, 'faceVerifyTokenGenerate']],
        ['word' => 'faceIdCardSubmit', 'provider' => [CmdWordService::class, 'faceIdCardSubmit']],
        ['word' => 'faceUploadMatchImage', 'provider' => [CmdWordService::class, 'faceUploadMatchImage']],
        ['word' => 'faceResultSimple', 'provider' => [CmdWordService::class, 'faceResultSimple']],
        ['word' => 'faceResultDetail', 'provider' => [CmdWordService::class, 'faceResultDetail']],
        ['word' => 'faceResultStat', 'provider' => [CmdWordService::class, 'faceResultStat']],
        ['word' => 'faceResultMediaQuery', 'provider' => [CmdWordService::class, 'faceResultMediaQuery']],
        ['word' => 'faceResultGetAll', 'provider' => [CmdWordService::class, 'faceResultGetAll']],
    ];

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerCmdWordProvider();
    }
}
