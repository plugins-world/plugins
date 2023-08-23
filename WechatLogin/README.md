# WechatLogin

## 使用方式

### 配置插件

- 访问 `/wechat-login/setting`
- 保存配置信息

### 使用命令字

1. 通过登录用户获取 account:
```php
$user = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountOfUser([
    'user' => $user,
]);

$account = $resp->getData('account');
```

2. 通过 account 获取 第一个 user:
```php
$account = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountFirstUser([
    'account' => $account,
]);

$user = $resp->getData('user');
```

3. 通过 account 获取 最后一个 user:
```php
$account = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountLastUser([
    'account' => $account,
]);

$user = $resp->getData('user');
```
