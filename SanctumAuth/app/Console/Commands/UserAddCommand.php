<?php

namespace Plugins\SanctumAuth\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '添加用户';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('请输入用户名');
        $email = $this->ask('请输入邮箱');
        $password = $this->secret('请输入密码');
        $passwordConfirmed = $this->secret('请再次输入密码');

        if (!$name) {
            $this->error('用户名不能为空');
            return 0;
        }

        if ($password !== $passwordConfirmed) {
            $this->error('密码不一致');
            return 0;
        }

        $this->table([
            'name', 'email',
        ], [
            [$name, $email]
        ]);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("{$name} 添加成功");
    }
}
