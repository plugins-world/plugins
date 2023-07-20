# PayCenter

## 使用方式

1. 通过命令字获取微信支付预下单信息:
```php
$wordBody = [
    'fskey' => 'DianCan',
    'cmdWord' => 'getOrderInfo',
    'wordBody' => [
        'account_id' => request('account_id'),
        'batchNo' => $batchNo,
    ],
    'payType' => 'mini',
];

$resp = \FresnsCmdWord::plugin('PayCenter')->wechatPay($wordBody);

dd($resp->getData());
```

2. 解析支付回调数据
```php
$wordBody = [
    'type' => 'pay_center_wechatpay',
];
$resp = \FresnsCmdWord::plugin('PayCenter')->callbackParse($wordBody);

dd($resp->getData());
```


2. 确认支付回调
```php
$wordBody = [
    'type' => 'pay_center_wechatpay',
];
$resp = \FresnsCmdWord::plugin('PayCenter')->callbackResponse($wordBody);

dd($resp->getData());
```
