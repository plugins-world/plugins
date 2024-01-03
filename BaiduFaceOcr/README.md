# Baidu Face OCR

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

3. H5-获取 verify_token
```php
$params = [
    'plan_id' => '1',  //方案的id信息
]; 

$resp = \FresnsCmdWord::plugin('BaiduOcr')->faceVerifyTokenGenerate($params);

dd($resp);
```

4. H5-指定用户上报
```php
$params = [
    'verify_token' => 'xxxx',
    'id_name' => '用户的姓名信息',
    'id_no' => '用户的身份证件号信息'
]; 

$resp = \FresnsCmdWord::plugin('BaiduOcr')->faceIdCardSubmit($params);

dd($resp);
```

5. H5-对比图片上传
```php
$params = [
    'verify_token' => 'xxxx',
    'image' => '图片base64字符串'
]; 

$resp = \FresnsCmdWord::plugin('BaiduOcr')->faceUploadMatchImage($params);

dd($resp);
```

6. H5-获取认证人脸
```php
$params = [
    'verify_token' => 'xxxx',
]; 

$resp = \FresnsCmdWord::plugin('BaiduOcr')->faceResultSimple($params);

dd($resp);
```

7. H5-查询认证结果
```php
$params = [
    'verify_token' => 'xxxx',
]; 

$resp = \FresnsCmdWord::plugin('BaiduOcr')->faceResultDetail($params);

dd($resp);
```

8. H5-查询统计结果
```php
$params = [
    'verify_token' => 'xxxx',
]; 

$resp = \FresnsCmdWord::plugin('BaiduOcr')->faceResultStat($params);

dd($resp);
```

9. H5-实时方案视频获取
```php
$params = [
    'verify_token' => 'xxxx',
]; 

$resp = \FresnsCmdWord::plugin('BaiduOcr')->faceResultMediaQuery($params);

dd($resp);
```

10. H5-核验及计费信息获取
```php
$params = [
    'verify_token' => 'xxxx',
]; 

$resp = \FresnsCmdWord::plugin('BaiduOcr')->faceResultGetAll($params);

dd($resp);
```
