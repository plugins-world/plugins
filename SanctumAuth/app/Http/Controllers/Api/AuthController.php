<?php

namespace Plugins\SanctumAuth\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use ZhenMu\Support\Traits\ResponseTrait;

class AuthController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $token = $this->generateTokenForUser($user);

        return $this->success([
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'token_name' => ['nullable', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->fail('用户名错误', 404);
        }

        if (Hash::check($request->passwkrd, $user->password)) {
            return $this->fail('密码错误', 401);
        }

        $token = $this->generateTokenForUser($user);

        return $this->success([
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('退出成功');
    }

    protected function generateTokenForUser($user)
    {
        $tokenName = \request('token_name') ?? 'api';
        $abalities = ['*'];
        $expiresAt = now()->addDays(7);

        $token = $user->createToken($tokenName, $abalities, $expiresAt);

        return $token->plainTextToken;
    }
}
