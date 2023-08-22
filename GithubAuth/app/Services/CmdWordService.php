<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\GithubAuth\Services;

use Plugins\GithubAuth\Utilities\GithubUtility;
use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function redirect(array $wordBody)
    {
        $redirect = $wordBody['redirect'] ?? null;

        $redirect = GithubUtility::redirect($redirect);

        return $this->success([
            'redirect' => $redirect,
        ]);
    }

    public function callback()
    {
        $accountConnect = GithubUtility::callback();

        return $this->success([
            'accountConnect' => $accountConnect,
        ]);
    }

    public function loginWeb(array $wordBody)
    {
        $accountConnect = GithubUtility::callback();
        $guard = $wordBody['guard'] ?? null;

        GithubUtility::loginWeb($accountConnect, $guard);

        return $this->success();
    }
}
