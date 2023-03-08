<?php

namespace Plugins\SsoServer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Plugins\SsoServer\Heplers\SsoHelper;
use Plugins\SsoServer\Heplers\UserHelper;
use ZhenMu\Support\Traits\ResponseTrait;

class SsoAuthCheck
{
    use ResponseTrait;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!UserHelper::ssoServerLoginCheck()) {
            if (\request()->wantsJson()) {
                return $this->fail('用户登录信息无效');
            } else {
                return redirect(SsoHelper::getLoginUrl(true));
            }
        }

        return $next($request);
    }
}
