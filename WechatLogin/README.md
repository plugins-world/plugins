# WechatLogin

## 使用方式

### 配置插件

- 访问 `/wechat-login/setting`
- 保存配置信息

### 使用命令字

- 创建 account 与 user
```php
$type = 3; // 1.超级管理员 / 2.普通管理员 / 3.普通用户
$aid = null;
$country_code = null;
$pure_phone = null;
$phone = null;
$email = null;
$password = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->addAccount([
    'type' => $type,
    'aid' => $aid,
    'country_code' => $country_code,
    'pure_phone' => $pure_phone,
    'phone' => $phone,
    'email' => $email,
    'password' => $password,
]);

$user = $resp->getData('user');
$accountUser = $resp->getData('accountUser');
$account = $resp->getData('account');
```


- 创建 user
```php
$account_id = null;
$aid = null;
$name = null;
$email = null;
$password = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->addUser([
    'account_id' => $account_id, // 用于关联账户
    'aid' => $aid, // 用户生成随机密码

    'name' => $name,
    'email' => $email,
    'password' => $password,
]);

$user = $resp->getData('user');
$accountUser = $resp->getData('accountUser');
```


- 为 user 生成 token
```php
$user = null;
$expiresAt = now()->addDays(7);
$tokenName = 'api';
$abalities = ['*'];

$resp = \FresnsCmdWord::plugin('WechatLogin')->generateTokenForUser([
    'user' => $user,
    'expiresAt' => $expiresAt,
    'tokenName' => $tokenName,
    'abalities' => $abalities,
]);

$token = $resp->getData('token');
```

- 通过登录用户获取 account:
```php
$user = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountOfUser([
    'user' => $user,
]);

$account = $resp->getData('account');
```


- 通过 accountId 获取 account:
```php
$accountId = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountByAccountId([
    'accountId' => $accountId,
]);

$account = $resp->getData('account');
```


- 通过 aid 获取 account:
```php
$aid = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountByAccountId([
    'aid' => $aid,
]);

$account = $resp->getData('account');
```


- 通过 mobile 获取 account:
```php
$mobile = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountByMobile([
    'mobile' => $mobile,
]);

$account = $resp->getData('account');
```


- 通过 email 获取 account:
```php
$email = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountByAccountId([
    'email' => $email,
]);

$account = $resp->getData('account');
```


- 通过 account 获取 第一个 user:
```php
$account = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountFirstUser([
    'account' => $account,
]);

$user = $resp->getData('user');
```


- 通过 account 获取 最后一个 user:
```php
$account = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountLastUser([
    'account' => $account,
]);

$user = $resp->getData('user');
```


- 获取 account 的授权信息:
```php
$account = null;
$connect_platform_id = 25;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountConnect([
    'account' => $account,
    'connect_platform_id' => $connect_platform_id,
]);

$accountConnect = $resp->getData('accountConnect');
```

- 获取 user 的授权信息:
```php
$user = null;
$connect_platform_id = 25;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountConnectOfUser([
    'user' => $user,
    'connect_platform_id' => $connect_platform_id,
]);

$accountConnect = $resp->getData('accountConnect');
```

- 获取 account 的头像昵称手机号等信息:
```php
$baseInfo = [];
$account = null;
$connect_platform_id = 25;

$resp = \FresnsCmdWord::plugin('WechatLogin')->loadAccountBaseInfo([
    'baseInfo' => $baseInfo,
    'account' => $account,
    'connect_platform_id' => $connect_platform_id,
]);

$baseInfo = $resp->getData('newBaseInfo');
```
