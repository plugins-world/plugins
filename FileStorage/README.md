# FileStorage 文件存储

文件存储插件目前支持本地存储与腾讯云 COS 存储。可以在插件设置页面切换、配置实用方式。


## 配置说明

- 存储驱动：
  - local：文件会保存在项目本地的 storage/app/public 目录下
  - 腾讯云 COS：文件会保存在腾讯云的 COS 存储中

当存储驱动为 `腾讯云 COS` 时，需要配置以下内容：

- 时区：默认 PRC，用于签名计算。
- APPID：腾讯云子账户的 AppID
- SecretId：腾讯云子账户的 SecretId
- SecretKey：腾讯云子账户的 SecretKey
- Reigon：腾讯云 cos 所在地域
- Bucket：腾讯云的 bucket
- 私有 Bucket：是否允许公有写
- 使用 https 链接：生成的链接是否是 https 的
- 文件域名：腾讯云 cos 提供的文件域名
- CDN 域名：腾讯云 cos 绑定的 cdn 域名

**配置信息可参考：https://github.com/overtrue/laravel-filesystem-cos#configuration**


## 命令字使用说明

1. 通过命令字上传文件:

```php
$type = 'image';
$usageType = 'avatars';
$file = \request()->file('file');

$resp = \FresnsCmdWord::plugin('FileStorage')->upload([
    'type' => $type,
    'usageType' => $usageType,
    'file' => $file,
]);

$fileInfo = $resp->getData();
```

2. 通过 file_id, file_path 获取 fileinfo

```php
$fileId = 1;
$filepath = null;
$temporary = false;

$resp = \FresnsCmdWord::plugin('FileStorage')->getFileInfo([
    'fileId' => $fileId,
    'filepath' => $filepath,
    'temporary' => false,
]);

$fileinfo = $resp->getData('fileinfo');
```

3. 通过 file_id, file_path 获取 getFileUrl

```php
$fileId = 1;
$filepath = null;

$resp = \FresnsCmdWord::plugin('FileStorage')->getFileUrl([
    'fileId' => $fileId,
    'filepath' => $filepath,
]);

$file_url = $resp->getData('file_url');
```

4. 通过 file_id, file_path 获取 getFileTemporaryUrl

```php
$fileId = 1;
$filepath = null;

$resp = \FresnsCmdWord::plugin('FileStorage')->getFileTemporaryUrl([
    'fileId' => $fileId,
    'filepath' => $filepath,
]);

$file_url = $resp->getData('file_url');
```


## 使用文档

暂未完善
