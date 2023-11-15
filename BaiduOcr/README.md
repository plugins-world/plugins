# Baidu OCR

## 命令字使用说明
1. 百度OCR公共请求方法

```php
$method = 'POST';              //文档中的请求方法，详细阅读 https://ai.baidu.com/ai-doc/REFERENCE/Ck3dwjhhu#2-%E8%8E%B7%E5%8F%96-access_token
$action = '/oauth/2.0/token';  //文档中的请求路由
$params = [];

$resp = \FresnsCmdWord::plugin('BaiduOcr')->request([
    'method' => $method,
    'action' => $action,
    'params' => $params,
]);

dd($resp);
```

2. 生成access_token
```php
$resp = \FresnsCmdWord::plugin('BaiduOcr')->tokenGenerate();

dd($resp);
```

3. 身份证识别
```php
// @see https://ai.baidu.com/ai-doc/OCR/rk3h7xzck
$params = [
    'url' => 'https://images0.cnblogs.com/blog/454646/201306/07090646-834eecbb94f8475a9a12026c50ef0dde.jpg',
    'id_card_side' => 'front',    //front:身份证含照片的一面/back:身份证带国徽的一面
];

$resp = \FresnsCmdWord::plugin('BaiduOcr')->idCardVerify($params);

dd($resp);
```

4. H5-港澳台通行证识别
```php
$params = [
    'image' => '本地文件路径',
    'exitentrypermit_type' => 'hk_mc_passport_front',    //hk_mc_passport_front：港澳通行证正面, hk_mc_passport_back：港澳通行证反面, tw_passport_front：台湾通行证正面, tw_passport_back：台湾通行证反面, tw_return_passport_front：台胞证正面, tw_return_passport_back：台胞证反面, hk_mc_return_passport_front：返乡证正面, hk_mc_return_passport_back：返乡证反面
]; 

$resp = \FresnsCmdWord::plugin('BaiduOcr')->hkAndTaiwanExitEntryPermit($params);

dd($resp);
```
