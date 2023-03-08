<?php

namespace Plugins\SsoClient\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Plugins\SsoClient\Heplers\SsoCookieHelper;
use Plugins\SsoClient\Utilities\StrUtility;

class SsoAuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $clientCookieName = SsoCookieHelper::getClientCookieName();
        $clientCookieValue = SsoCookieHelper::getClientCookieValue();

        $serverCookieName = SsoCookieHelper::getServerCookieName();
        $serverCookieValue = SsoCookieHelper::getServerCookieValue();

        $currentUrl = \request()->fullUrlWithoutQuery(['return_url', $serverCookieName]);

        $loginUrl = StrUtility::getSsoServerUrl('/sso-server/sso?').http_build_query([
            'return_url' => $currentUrl,
        ]);

        $serverValue = SsoCookieHelper::getServerToken();
        $serverLoginValueValidate = false;

        if ($serverValue) {
            $serverLoginValueValidate = SsoCookieHelper::serverLoginCheck($serverValue);
            // $serverLoginValueValidate = false;

            if (!$serverLoginValueValidate) {
                SsoCookieHelper::logoutUser();
                return redirect($loginUrl);
            } else {
                SsoCookieHelper::loginUser($serverValue);
                
                if (\request()->has($serverCookieName)) {
                    return redirect($currentUrl);
                }
            }
        }

        if (!$clientCookieValue || !$serverCookieValue) {
            SsoCookieHelper::logoutUser();
            return redirect($loginUrl);
        }

        return $next($request);
    }
}
