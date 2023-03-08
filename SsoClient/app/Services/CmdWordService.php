<?php

namespace Plugins\SsoClient\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;

class CmdWordService
{
    use CmdWordResponseTrait;
    
    public function updateUserInfo(array $wordBody)
    {
        $data = [];
        $data['uid'] = $wordBody['uid'];
        $data['username'] = $wordBody['username'];
        $data['nickname'] = $wordBody['nickname'];
        $data['avatar'] = $wordBody['avatar'];

        if (!class_exists(User::class)) {
            return $this->failure(40001, User::class . '不存在');
        }

        $user = User::where([
            'name' => $data['username'],
        ])->first();
        if (!$user) {
            $email = $data['username'].'@example.com';

            $user = User::create([
                'name' => $data['username'],
                'email' => $email,
                'password' => Hash::make($email)
            ]);
        }

        return $this->success();
    }
}