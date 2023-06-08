<?php

namespace Plugins\LaravelLocalStorage\Providers;

use Plugins\LaravelLocalStorage\Services\CmdWordService;
use Illuminate\Support\ServiceProvider;

class CmdWordServiceProvider extends ServiceProvider implements \Fresns\CmdWordManager\Contracts\CmdWordProviderContract
{
    use \Fresns\CmdWordManager\Traits\CmdWordProviderTrait;

    protected $fsKeyName = 'LaravelLocalStorage';

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