# WechatLogin

微信登录插件，目前支持微信小程序登录功能，提供了 api 支持。也是其他第三方登录插件的基础依赖。管理着 accounts、account_connects、account_users 等相关第三方登录基础表。


### 命令字

1. 通过登录用户获取 account:
```php
$user = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountOfUser([
    'user' => $user,
]);

$account = $resp->getData('account');
```

2. 通过登录用户获取 account:
```php
$accountId = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountByAccountId([
    'accountId' => $accountId,
]);

$account = $resp->getData('account');
```

3. 通过 account 获取 第一个 user:
```php
$account = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountFirstUser([
    'account' => $account,
]);

$user = $resp->getData('user');
```

4. 通过 account 获取 最后一个 user:
```php
$account = null;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountLastUser([
    'account' => $account,
]);

$user = $resp->getData('user');
```


5. 获取 account 的授权信息:
```php
$account = null;
$connect_platform_id = 25;

$resp = \FresnsCmdWord::plugin('WechatLogin')->getAccountConnect([
    'account' => $account,
    'connect_platform_id' => $connect_platform_id,
]);

$accountConnect = $resp->getData('accountConnect');
```
