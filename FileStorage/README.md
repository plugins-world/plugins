# FileStorage

## 使用方式

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

$resp = \FresnsCmdWord::plugin('FileStorage')->getFileInfo([
    'fileId' => $fileId,
    'filepath' => $filepath,
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
