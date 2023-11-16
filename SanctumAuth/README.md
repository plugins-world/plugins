# SanctumAuth

Laravel 项目初始用户创建管理，sanctum auth 登录，生成 api token。


## 命令字

1. 获取用户登录的 api token:
```php
$user = \App\Models\User::first();
$tokenName = 'sanctum';
$abalities = ['*']; // array or null
$expiresAt = now()->addDays(7); // expiresAt

$resp = \FresnsCmdWord::plugin('SanctumAuth')->generateTokenForUser([
    'user' => $user,
    'tokenName' => $tokenName,
    'abalities' => $abalities,
    'expiresAt' => $expiresAt,
]);

$token = $resp->getData('token');
```
