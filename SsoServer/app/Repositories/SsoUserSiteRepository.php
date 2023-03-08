<?php

namespace Plugins\SsoServer\Repositories;

use Laravel\Sanctum\NewAccessToken;
use Plugins\SsoServer\Models\SsoUser;
use Laravel\Sanctum\PersonalAccessToken;
use Plugins\SsoServer\Models\SsoUserSite;
use Plugins\SsoServer\Utilities\StrUtility;

class SsoUserSiteRepository
{
    public function findUserTokenByTokenName(SsoUser $user, string $tokenName): null|NewAccessToken|PersonalAccessToken
    {
        $token = $user->tokens()->where([
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->id,
            'name' => $tokenName,
        ])->first();

        return $token;
    }

    public function findUserSiteTokenByToken(string $token): ?SsoUserSite
    {
        $ssoUserSite = SsoUserSite::query()
            ->where([
                'token' => $token
            ])
            ->first();

        return $ssoUserSite;
    }

    public function createUserToken(SsoUser $user, string $tokenName, \DateTimeInterface $expiredAt = null): NewAccessToken
    {
        $token = $this->findUserTokenByTokenName($user, $tokenName);

        if ($token?->expired_at?->isPast()) {
            // throw new \RuntimeException('token 已过期');
            \info('token 已过期');
            $token = null;
        }

        if (!$token) {
            $token = $user->createToken($tokenName, ['*'], $expiredAt);
        } else {
            $token = new NewAccessToken($token, $token->token);

            $user->withAccessToken($token);
        }

        return $token;
    }

    public function createSsoUserSiteForUser(SsoUser $user, array $data): ?SsoUserSite
    {
        $tokenName = 'sso-server';
        $token = $this->createUserToken($user, $tokenName, now()->addMinutes(config('sanctum.expiration')));

        $data['usid'] = StrUtility::generateSsoId(); // 暂时随机生成，后续替换为辅助函数

        $ssoUserSite = SsoUserSite::query()->where([
            'site_domain' => $data['site_domain'],
            'uid' => $user->uid,
        ])->first();
        
        if ($ssoUserSite?->expire_time->isPast()) {
            $this->remoteSsoUserSiteWithToken($user, $data['site_domain']);
        }

        if (!$ssoUserSite) {
            $ssoUserSite = SsoUserSite::query()->create([
                'usid' => $data['usid'],
                'site_domain' => $data['site_domain'],
                'uid' => $user->uid,
                'is_login' => true,
                'token' => $token->accessToken->token,
                'expire_time' => $token?->accessToken?->expires_at ?? null,
            ]);
        }

        return $ssoUserSite;
    }

    public function remoteSsoUserSiteWithToken(?SsoUser $user, $domain)
    {
        if (!$user) {
            return null;
        }

        SsoUserSite::query()->where([
            'site_domain' => $domain,
            'token' => $user->currentAccessToken()->accessToken->token,
        ])->delete();

        $user->currentAccessToken()->accessToken?->delete();

        return true;
    }
}
