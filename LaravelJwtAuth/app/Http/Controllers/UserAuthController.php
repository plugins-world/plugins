<?php

namespace Plugins\LaravelJwtAuth\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Plugins\LaravelJwtAuth\Models\User;

class UserAuthController extends AdministratorAuthController
{
    public function getUserModel(): string
    {
        return User::class;
    }
    
    public function login()
    {
        \request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $userModel = $this->getUserModel();

        $user = $userModel::where('name', \request('username'))->first();

        if (is_null($user)) {
            return $this->fail('账号不存在', 404);
        }

        if (! Hash::check(\request('password'), $user->password)) {
            return $this->fail('密码不正确', 401);
        }

        $token = $this->guard()->login($user);

        return $this->success($this->respondWithToken($token));
    }

    protected function guard(): \Tymon\JWTAuth\JWTGuard
    {
        return auth('api');
    }
}
