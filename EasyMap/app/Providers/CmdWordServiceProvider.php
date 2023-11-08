<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\EasyMap\Providers;

use Fresns\CmdWordManager\Contracts\CmdWordProviderContract;
use Fresns\CmdWordManager\Traits\CmdWordProviderTrait;
use Illuminate\Support\ServiceProvider;
use Plugins\EasyMap\Services\CmdWordService;

class CmdWordServiceProvider extends ServiceProvider implements CmdWordProviderContract
{
    use CmdWordProviderTrait;

    protected $fsKeyName = 'EasyMap';

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
        ['word' => 'request', 'provider' => [CmdWordService::class, 'request']],
        ['word' => 'getGeoCode', 'provider' => [CmdWordService::class, 'getGeoCode']]
    ];

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerCmdWordProvider();
    }
}
