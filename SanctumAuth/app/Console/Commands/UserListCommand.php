<?php

namespace Plugins\SanctumAuth\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '查看用户列表';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userData = [];

        $users = User::all();
        foreach ($users as $user) {
            $item['id'] = $user['id'];
            $item['name'] = $user['name'];
            $item['email'] = $user['email'];

            $userData[] = $item;
        }

        $this->table([
            'id', 'name', 'email',
        ], $userData);
    }
}
