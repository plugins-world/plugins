# GithubAuth

## 使用方式

### 配置插件

- 访问 `/github-auth/setting`
- 保存配置信息

### 使用命令字

1. 获取用户登录 token:
```php
$redirect = route('github-auth.auth.index');

$resp = \FresnsCmdWord::plugin('GithubAuth')->redirect([
    'redirect' => $redirect,
]);

$redirect = $resp->getData('redirect');
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
