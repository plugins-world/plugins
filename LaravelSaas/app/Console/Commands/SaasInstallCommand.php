<?php

namespace Plugins\LaravelSaas\Console\Commands;

use Illuminate\Console\Command;

class SaasInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saas:install
        {--domain= : saas domain, add into config/tenancy.php, such as saas.test}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化 stancl/tenancy 3.x: https://tenancyforlaravel.com/docs/v3/quickstart';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('migrate');

        $this->registerProvider();
        $this->registerRoutes();
        // $this->registerDomains($this->option('domain'));
        $this->createTenantMigrationsDir();
        $this->setTenantPrefix();
        $this->addInitTenantAction();
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        $content = file_get_contents($path);
        if (! str_contains($content, $replace)) {
            file_put_contents($path, str_replace($search, $replace, $content));
        }
    }

    public function registerProvider()
    {
        $this->replaceInFile('App\Providers\RouteServiceProvider::class,', 'App\Providers\RouteServiceProvider::class,'.PHP_EOL."        App\Providers\TenancyServiceProvider::class, // <-- here", config_path('app.php'));
    }

    public function registerRoutes()
    {
        $this->replaceInFile(<<<'TXT'
            public function boot(): void
            {
                RateLimiter::for('api', function (Request $request) {
                    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
                });
        
                $this->routes(function () {
                    Route::middleware('api')
                        ->prefix('api')
                        ->group(base_path('routes/api.php'));
        
                    Route::middleware('web')
                        ->group(base_path('routes/web.php'));
                });
            }
        TXT
        ,<<<'TXT'
            public function boot(): void
            {
                RateLimiter::for('api', function (Request $request) {
                    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
                });
        
                $this->routes(function () {
                    foreach ($this->centralDomains() as $domain) {
                        Route::middleware('api')
                            ->domain($domain)
                            ->prefix('api')
                            ->group(base_path('routes/api.php'));

                        Route::middleware('web')
                            ->domain($domain)
                            ->group(base_path('routes/web.php'));
                    }
                });
            }

            protected function centralDomains(): array
            {
                return config('tenancy.central_domains');
            }
        TXT, app_path('Providers/RouteServiceProvider.php'));
    }

    public function registerDomains($domain = null)
    {
        if (! $domain) {
            $urlInfo = parse_url(config('app.url'));

            $domain = $urlInfo['host'];
        }

        if (! $domain) {
            return;
        }

        $this->replaceInFile(<<<'TXT'
            'central_domains' => [
                '127.0.0.1',
                'localhost',
            ],
        TXT
        ,<<<"TXT"
            'central_domains' => [
                '$domain',
                '127.0.0.1',
                'localhost',
            ],
        TXT, config_path('tenancy.php'));
    }

    public function createTenantMigrationsDir()
    {
        $path = database_path('migrations/tenant');

        if (!is_dir($path)) {
            @mkdir($path, 0755, true);
            @touch($path.'/.gitkeep');
        }
    }

    public function setTenantPrefix()
    {
        $content = file_get_contents($filePath = config_path('tenancy.php'));

        if (str_contains($content, "url_override")) {
            return;
        }

        $newContent = str_replace(
            [
                "use Stancl\Tenancy\Database\Models\Tenant;\n\nreturn [",
                "'tenant_model' => Tenant::class,",
                "'localhost',\n    ],",
                "'prefix' => 'tenant',",
                "_base' => 'tenant',",
                "// '--force' => true,",
                "'public' => '%storage_path%/app/public/',\n        ],",
            ],
            [
                "use Stancl\Tenancy\Database\Models\Tenant;\n\n\$prefix = env('DB_DATABASE') . '_';\n\nreturn [",
                "'tenant_model' => \App\Models\Tenant::class,",
                "'localhost',\n\t\tstr_replace(['http://', 'https://'], '', trim(env('APP_URL', ''), '/')),\n    ],",
                "'prefix' => \$prefix,",
                "_base' => \$prefix,",
                "'--force' => true,",
                "'public' => '%storage_path%/app/public/',
        ],

        /*
        * Use this to support Storage url method on local driver disks.
        * You should create a symbolic link which points to the public directory using command: artisan tenants:link
        * Then you can use tenant aware Storage url: Storage::disk('public')->url('file.jpg')
        *
        * See https://github.com/archtechx/tenancy/pull/689
        */
        'url_override' => [
            // The array key is local disk (must exist in root_override) and value is public directory (%tenant_id% will be replaced with actual tenant id).
            'public' => 'tenants/public-%tenant_id%',
        ],",
            ],
            $content
        );
        file_put_contents($filePath, $newContent);
    }

    public function addInitTenantAction()
    {
        $tenantModelFile = app_path('Models/Tenant.php');

        if (file_exists($tenantModelFile)) {
            return;
        }

        copy(__DIR__.'/stubs/tenant_model.stub', app_path('Models/Tenant.php'));
        
        $content = file_get_contents($filePath = base_path('app/Providers/TenancyServiceProvider.php'));

        $newContent = str_replace(
            [
                "// Jobs\SeedDatabase::class,",
                "send(function (Events\TenantCreated \$event) {
                    return \$event->tenant;
                })",
                "send(function (Events\TenantDeleted \$event) {
                    return \$event->tenant;
                })",
            ],
            [
                "Jobs\SeedDatabase::class,",
                "send(function (Events\TenantCreated \$event) {
                    \App\Models\Tenant::createStorageLink(\$event->tenant); // <-- here.
                    return \$event->tenant;
                })",
                "send(function (Events\TenantDeleted \$event) {
                    \App\Models\Tenant::removeStorageLink(\$event->tenant); // <-- here.
                    return \$event->tenant;
                })",
            ],
            $content
        );
        file_put_contents($filePath, $newContent);
    }
}
