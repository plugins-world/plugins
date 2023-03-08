<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\SystemAuthorization\Providers;

use Illuminate\Support\ServiceProvider;

class CmdWordServiceProvider extends ServiceProvider implements \Fresns\CmdWordManager\Contracts\CmdWordProviderContract
{
    use \Fresns\CmdWordManager\Traits\CmdWordProviderTrait;

    protected $unikeyName = 'SystemAuthorization';

    /**
     *
     * @example

    use Plugins\BarBaz\Models\TestModel;
    use Plugins\BarBaz\Services\AWordService;
    use Plugins\BarBaz\Services\BWordService;

    protected $cmdWordsMap = [
        ['word' => 'cmdWord', 'provider' => [CmdWordService::class, 'cmdWord']],
        ['word' => AWordService::CMD_TEST, 'provider' => [AWordService::class, 'handleTest']],
        ['word' => BWordService::CMD_STATIC_TEST, 'provider' => [BWordService::class, 'handleStaticTest']],
        ['word' => TestModel::CMD_MODEL_TEST, 'provider' => [TestModel::class, 'handleModelTest']],
    ];

     * @var array[]
     */
    protected $cmdWordsMap = [
        ['word' => 'issueCode', 'provider' => [\Plugins\SystemAuthorization\Services\CmdWordService::class, 'issueCode']],
        ['word' => 'revokeCode', 'provider' => [\Plugins\SystemAuthorization\Services\CmdWordService::class, 'revokeCode']],
        ['word' => 'validateCode', 'provider' => [\Plugins\SystemAuthorization\Services\CmdWordService::class, 'validateCode']],
        ['word' => 'removeCode', 'provider' => [\Plugins\SystemAuthorization\Services\CmdWordService::class, 'removeCode']],
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCmdWordProvider();
    }
}