# PayCenter

- 支付中心，目前仅支持微信支付，在插件设置页配置好微信支付的相关信息后，即可使用微信支付功能。
- 关于预付单配置，依赖于业务插件自行提供订单数据。由微信支付获取订单数据信息后，提交给微信，微信再生成预付单配置信息。微信回调后需要更新订单状态，并给到回调应答响应。均通过调用命令字完成。此处涉及插件依赖问题，通过命令字 RPC 完成。解耦业务系统。

## 配置说明

详细配置说明见：https://pay.yansongda.cn/docs/v3/quick-start/init.html


## 插件流程时序图

<img src="https://marketplace.plugins-world.cn/storage/images/app_images/202309/05/支付流程时序图.jpg" data-image-url="https://marketplace.plugins-world.cn/storage/images/app_images/202309/05/支付流程时序图.jpg" alt="支付流程时序图" title="支付流程时序图" class="w-100 image_preview">

## 命令字

1. 获取微信支付预付单信息:
```php
$wordBody = [
    'payPlatform' => 'wechat',
    'orderAction' => 'mini',
    'init_config_key' => 'pay_center_wechatpay',

    'rpc' => [
        'fskey' => 'DianCan', // 业务插件名
        'cmdWord' => 'getOrderInfo', // 业务插件获取微信下单结构的命令字
        'wordBody' => [ // 业务插件查询订单需要的参数数据
            'account_id' => request('account_id'),
            'batchNo' => $batchNo,
        ],
    ]
];

$resp = \FresnsCmdWord::plugin('PayCenter')->handle($wordBody);

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


## 使用文档

暂未完善
