<?php

namespace Plugins\Tenant\Console\Commands;

use Illuminate\Console\Command;

class SaasListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saas:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List tenants.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('tenants:list');
    }
}
