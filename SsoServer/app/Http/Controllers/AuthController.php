<?php

namespace Plugins\SsoServer\Http\Controllers;

use App\Http\Controllers\Controller;
use Plugins\SsoServer\Heplers\SsoCookieHelper;
use Plugins\SsoServer\Heplers\SsoHelper;
use Plugins\SsoServer\Heplers\UserHelper;

class AuthController extends Controller
{
    public function index()
    {
        // 未登录
        if (!UserHelper::ssoServerLoginCheck()) {
            return redirect(SsoHelper::getLogoutUrl(true));
        }

        // 已登录
        $returnUrl = SsoCookieHelper::getClientAccessUrl();
        if ($returnUrl) {
            return redirect($returnUrl);
        }

        return view('SsoServer::index');
    }

    public function showLoginForm()
    {
        return view('SsoServer::auth/login');
    }

    public function login()
    {
        \request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = \request('username');
        $password = \request('password');

        try {
            UserHelper::userLogin($username, $password);
        } catch (\Throwable $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect(SsoHelper::getIndexUrl(true));
    }

    public function logout()
    {
        UserHelper::userLogout();

        return redirect(SsoHelper::getLoginUrl(true));
    }

    public function showRegisterForm()
    {
        return view('SsoServer::auth/register');
    }

    public function register()
    {
        \request()->validate([
            'username' => 'required',
            'password' => 'required|confirmed',
        ]);

        $data = [];
        $data['username'] = \request('username');
        $data['password'] = \request('password');
        $data['password_confirmation'] = \request('password_confirmation');

        try {
            UserHelper::userRegister($data);
            // 注册成功自动登录
            UserHelper::userLogin($data['username'], $data['password']);
        } catch (\Throwable $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect(SsoHelper::getIndexUrl(true));
    }

    public function sso()
    {
        // 登录信息无效
        if (!UserHelper::ssoServerLoginCheck()) {
            return redirect(SsoHelper::getLogoutUrl(true));
        }

        // 来自 sso server 登录
        return redirect(SsoHelper::getIndexUrl(true));
    }

    public function ssoService()
    {
        // 验证 sso 信息是否有效，有效则颁发获取用户信息的 token，可采用 rsa 加密

        // 无效则告知客户端
    }
}
