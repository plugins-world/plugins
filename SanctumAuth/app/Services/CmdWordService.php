<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\SanctumAuth\Services;

use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;
use Plugins\SanctumAuth\Utilities\UserAuthUtility;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function generateTokenForUser(array $wordBody)
    {
        $user = $wordBody['user'];
        $tokenName = $wordBody['tokenName'] ?? 'sanctum';
        $abalities = $wordBody['abalities'] ?? ['*'];
        $expiresAt = $wordBody['expiresAt'] ?? null;

        $token = UserAuthUtility::generateTokenForUser($user, $tokenName, $abalities, $expiresAt);

        return $this->success([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
