# GithubAuth

## 使用方式

1. 获取用户登录 token:
```php
$redirect = route('github-auth.auth.index');

$resp = \FresnsCmdWord::plugin('GithubAuth')->generateTokenForUser([
    'user' => $user,
    'tokenName' => $tokenName,
]);

$token = $resp->getData('token');
```

2. 获取授权关联信息 accountConnect:
```php
$resp = \FresnsCmdWord::plugin('GithubAuth')->callback();

$accountConnect = $resp->getData('accountConnect');
```

3. web 登录:
```php
$guard = 'web' ?? null;
$resp = \FresnsCmdWord::plugin('GithubAuth')->loginWeb([
    'guard' => $guard,
]);
```
