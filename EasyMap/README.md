# EasyMap

## 使用法法
1. 高德地图公共请求方法
```php
$method = 'GET';              //文档中的请求方法，详细阅读 https://lbs.amap.com/api/webservice/guide/api/georegeo
$action = '/v3/geocode/geo';  //文档中的请求路由
$params = [
    'address' => '成都市高新区吉泰路666号福年广场T2',
];

$resp = \FresnsCmdWord::plugin('EasyMap')->request([
    'method' => $method,
    'action' => $action,
    'params' => $params,
]);

dd($resp);
```

2. 地址转地理坐标
```php
$address = sprintf('%s%s%s%s', request('province_ext_name'), request('city_ext_name'), request('area_ext_name'), request('address'));
$address = '成都市高新区吉泰路666号福年广场T2';

$resp = \FresnsCmdWord::plugin('EasyMap')->getGeoCodeGeoInfo([
    'address' => $address,
]);

if ($resp->isErrorResponse()) {
    $errorMessage = $resp->getMessage();
}

$resp->getData('longitude'); // 经度
$resp->getData('latitude'); // 纬度
dd($resp);
```

3. 经纬度转地址
```php
$wordBody['longitude'] = $longitude;
$wordBody['latitude'] = $latitude;
$wordBody['user_address'] = $user_address;
$resp = \FresnsCmdWord::plugin('EasyMap')->getGeoCodeRegeoInfo($wordBody);
if ($resp->isErrorResponse()) {
    $errorMessage = $resp->getMessage();
}

$resp->getData(); // 转换结果
dd($resp);
```
