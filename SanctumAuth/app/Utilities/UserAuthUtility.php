<?php

namespace Plugins\SanctumAuth\Utilities;

class UserAuthUtility
{
    public static function generateTokenForUser($user, $tokenName = 'sanctum', $abalities = ['*'], $expiresAt = null)
    {
        $expiresAt = $expiresAt ?? now()->addDays(7);

        $token = $user->createToken($tokenName, $abalities, $expiresAt);

        return $token->plainTextToken;
    }
}