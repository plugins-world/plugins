<?php

namespace Plugins\LaravelJwtAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Plugins\LaravelJwtAuth\Models\Administrator;

class AdministratorAuthController extends Controller
{
    public function getUserModel(): string
    {
        return Administrator::class;
    }
    
    public function login()
    {
        \request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $userModel = $this->getUserModel();

        $user = $userModel::where('username', \request('username'))->first();

        if (is_null($user)) {
            return $this->fail('账号不存在', 404);
        }

        if (! Hash::check(\request('password'), $user->password)) {
            return $this->fail('密码不正确', 401);
        }

        $token = $this->guard()->login($user);

        return $this->success($this->respondWithToken($token));
    }

    public function me()
    {
        $user = $this->guard()->user();
        if (!$user) {
            return $this->fail('请登录');
        }

        return $this->success($user?->getDetail() ?? $user->toArray());
    }

    public function logout()
    {
        auth()->logout();

        return $this->success('退出成功');
    }

    public function refresh()
    {
        return $this->success($this->respondWithToken($this->guard()->refresh()));
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ];
    }

    protected function guard(): \Tymon\JWTAuth\JWTGuard
    {
        return auth('api-admin');
    }
}
