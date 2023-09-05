# SanctumAuth

Laravel 项目初始用户创建管理，sanctum auth 登录，生成 api token。


## 命令字

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
