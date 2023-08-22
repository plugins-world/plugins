<?php

namespace Plugins\GithubAuth\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Plugins\GithubAuth\Utilities\GithubUtility;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('GithubAuth::pages.login.index', [
            'configs' => $configs,
        ]);
    }

    public function redirect()
    {
        return GithubUtility::redirect();
    }

    public function callback()
    {
        request()->validate([
            'code' => ['required', 'string'],
            'state' => ['nullable', 'string'],
        ]);

        $accountConnect = GithubUtility::callback();

        return back()->with('success', '登录成功：'.$accountConnect['connect_nickname']);
    }
}
