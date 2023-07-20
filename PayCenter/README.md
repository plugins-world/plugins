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
