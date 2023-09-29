# SmsAuth

## 使用方式

1. 接口信息请查看 api.php 路由文件

3. 通过命令字获取发送短信信息:
```
// 业务插件需要提供命令字，返回登录接口的参数数据
public function handleSmsAction(array $wordBody)
{
    $actionType = $wordBody['actionType'];
    $to = $wordBody['to'];
    $data = $wordBody['data'] ?? [];
    $data['sign_name'] = '插件世界';

    switch ($actionType) {
        case 'login':
            $params['content'] = null;
            $params['template'] = '1686043';
            break;
    }

    $params['data'] = $data;

    return $this->success([
        'to' => $to,
        'params' => $params,
    ]);
}
```