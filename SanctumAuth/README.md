# SanctumAuth

## 使用方式

1. 获取用户登录的 api token:
```php
$user = \App\Models\User::first();
$tokenName = 'sanctum';

$resp = \FresnsCmdWord::plugin('SanctumAuth')->generateTokenForUser([
    'user' => $user,
    'tokenName' => $tokenName,
]);

$token = $resp->getData('token');
```
