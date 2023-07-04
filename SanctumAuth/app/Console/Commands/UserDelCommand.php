<?php

namespace Plugins\SanctumAuth\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserDelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-del';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除用户';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('请输入用户名');
        if (!$name) {
            $this->error('用户名不能为空');
            return 0;
        }

        $user = User::where('name', $name)->first();
        if (!$user) {
            $this->error("{$name} 用户不存在");
            return 0;
        }

        $name = $user['name'];
        $email = $user['email'];

        $this->table([
            'name', 'email',
        ], [
            [$name, $email]
        ]);

        if ($this->confirm('确认删除?', true)) {
            $user->delete();
        }
    }
}
