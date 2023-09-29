<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\WechatLogin\Providers;

use Fresns\CmdWordManager\Contracts\CmdWordProviderContract;
use Fresns\CmdWordManager\Traits\CmdWordProviderTrait;
use Illuminate\Support\ServiceProvider;
use Plugins\WechatLogin\Services\CmdWordService;

class CmdWordServiceProvider extends ServiceProvider implements CmdWordProviderContract
{
    use CmdWordProviderTrait;

    protected $fsKeyName = 'WechatLogin';

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
        ['word' => 'addAccount', 'provider' => [CmdWordService::class, 'addAccount']],
        ['word' => 'addUser', 'provider' => [CmdWordService::class, 'addUser']],
        ['word' => 'generateTokenForUser', 'provider' => [CmdWordService::class, 'generateTokenForUser']],
        ['word' => 'getAccountOfUser', 'provider' => [CmdWordService::class, 'getAccountOfUser']],
        ['word' => 'getAccountByAccountId', 'provider' => [CmdWordService::class, 'getAccountByAccountId']],
        ['word' => 'getAccountByAId', 'provider' => [CmdWordService::class, 'getAccountByAId']],
        ['word' => 'getAccountByMobile', 'provider' => [CmdWordService::class, 'getAccountByMobile']],
        ['word' => 'getAccountByEmail', 'provider' => [CmdWordService::class, 'getAccountByEmail']],
        ['word' => 'getAccountFirstUser', 'provider' => [CmdWordService::class, 'getAccountFirstUser']],
        ['word' => 'getAccountLastUser', 'provider' => [CmdWordService::class, 'getAccountLastUser']],
        ['word' => 'getAccountConnect', 'provider' => [CmdWordService::class, 'getAccountConnect']],
    ];

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerCmdWordProvider();
    }
}
