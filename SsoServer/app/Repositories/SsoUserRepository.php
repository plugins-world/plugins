<?php

namespace Plugins\SsoServer\Repositories;

use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Plugins\SsoServer\Models\SsoUser;
use Plugins\SsoServer\Utilities\StrUtility;

class SsoUserRepository
{
    public function find(int $userId): ?SsoUser
    {
        $user = SsoUser::where('id', $userId)->first();

        return $user;
    }

    public function findByUserUid(string $uid): ?SsoUser
    {
        $user = SsoUser::where('uid', $uid)->first();

        return $user;
    }

    public function findUserByUsername(string $userUsername): ?SsoUser
    {
        $user = SsoUser::where('username', $userUsername)->first();

        return $user;
    }

    public function findUserByToken(?string $tokenValue): ?SsoUser
    {
        if (!$tokenValue) {
            return null;
        }
        
        $personalAccessToken = PersonalAccessToken::query()->where('token', $tokenValue)->first();
        if (!$personalAccessToken) {
            return null;
        }

        /** @var SsoUser $user */
        $user = $personalAccessToken->tokenable;
        $user->withAccessToken(new NewAccessToken($personalAccessToken, $personalAccessToken->token));

        return $user;
    }

    public function addUser(array $params)
    {
        $data = [];
        $data['username'] = $params['username'];
        $data['password'] = $params['password'];
        $data['uid'] = StrUtility::generateSsoId(); // 暂时随机生成，后续替换为辅助函数

        $user = $this->findUserByUsername($data['username']);

        if ($user) {
            throw new \RuntimeException('添加用户失败，用户已存在');
        }

        $user = SsoUser::create($data);

        return $user;
    }
}
