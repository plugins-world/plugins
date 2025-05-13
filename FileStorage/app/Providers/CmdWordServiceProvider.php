<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\FileStorage\Providers;

use Fresns\CmdWordManager\Contracts\CmdWordProviderContract;
use Fresns\CmdWordManager\Traits\CmdWordProviderTrait;
use Illuminate\Support\ServiceProvider;
use Plugins\FileStorage\Services\CmdWordService;

class CmdWordServiceProvider extends ServiceProvider implements CmdWordProviderContract
{
    use CmdWordProviderTrait;

    protected $fsKeyName = 'FileStorage';

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
        ['word' => 'initConfig', 'provider' => [CmdWordService::class, 'initConfig']],
        ['word' => 'upload', 'provider' => [CmdWordService::class, 'upload']],
        ['word' => 'getUploadToken', 'provider' => [CmdWordService::class, 'getUploadToken']],
        ['word' => 'fresnsFileStoragePath', 'provider' => [CmdWordService::class, 'fresnsFileStoragePath']],
        ['word' => 'uploadFile', 'provider' => [CmdWordService::class, 'uploadFile']],
        ['word' => 'getFileInfo', 'provider' => [CmdWordService::class, 'getFileInfo']],
        ['word' => 'getFileUrl', 'provider' => [CmdWordService::class, 'getFileUrl']],
        ['word' => 'getFileTemporaryUrl', 'provider' => [CmdWordService::class, 'getFileTemporaryUrl']],
    ];

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerCmdWordProvider();
    }
}
