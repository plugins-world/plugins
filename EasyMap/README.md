# EasyMap

## 使用法法

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
