# EasySms

## 使用方式

1. 发送短信

```php
$to = '12345678901';
$params = [
    'template' => '1686043',
    'data' => [
        'sign_name' => '插件世界', // 可以通过设置项进行配置
        '{1}' => '1234',
    ],
];

$resp = \FresnsCmdWord::plugin('EasySms')->send([
    'to' => $to,
    'params' => $params,
]);

dd($resp);
```
