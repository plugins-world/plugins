<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\LaravelQiNiu\Providers;

use Illuminate\Support\ServiceProvider;
use Plugins\LaravelQiNiu\Services\CmdWordService;

class CmdWordServiceProvider extends ServiceProvider implements \Fresns\CmdWordManager\Contracts\CmdWordProviderContract
{
    use \Fresns\CmdWordManager\Traits\CmdWordProviderTrait;

    protected $unikeyName = 'LaravelQiNiu';

    /**
     *
     * @example

    use Plugins\BarBaz\Models\TestModel;
    use Plugins\BarBaz\Services\AWordService;
    use Plugins\BarBaz\Services\BWordService;

    protected $cmdWordsMap = [
        ['word' => AWordService::CMD_TEST, 'provider' => [AWordService::class, 'handleTest']],
        ['word' => BWordService::CMD_STATIC_TEST, 'provider' => [BWordService::class, 'handleStaticTest']],
        ['word' => TestModel::CMD_MODEL_TEST, 'provider' => [TestModel::class, 'handleModelTest']],
    ];

     * @var array[]
     */
    protected $cmdWordsMap = [
        ['word' => 'getToken', 'provider' => [CmdWordService::class, 'getToken']],
        ['word' => 'upload', 'provider' => [CmdWordService::class, 'upload']],
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