<?php

namespace Plugins\FileStorage\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Plugins\FileStorage\Models\File;
use Plugins\FileStorage\Utilities\FileUtility;
use ZhenMu\Support\Traits\ResponseTrait;

class FileStorageController extends Controller
{
    use ResponseTrait;

    public function fileUpload()
    {
        request()->validate([
            'type' => ['nullable', 'string', Rule::in(array_keys(File::TYPE_MAP))],
            'usage_type' => ['nullable', 'string'], // 自行通过文档进行约束说明: usage_type: avatars-头像;...
            'file' => ['required_if:file_name,null', 'file'],

            'filename' => ['required_if:file,null', 'string'],
            'mime' => ['required_if:file,null', 'string'],
            'size' => ['required_if:file,null', 'integer'],
        ]);

        $type = request('type');
        $usageType = request('usage_type');
        $file = request('file');
        $filename = request('filename');
        $mime = request('mime');
        $size = request('size');

        // $path = storage_path("app/public/{$usageType}/merchants");

        $tenantNo = request()->user()?->tenant_no ?? null;
        $day = date("Y-m-d");
        $time = time();

        $savePath = match ($usageType) {
            //  tenant_no/merchant_images/商户名称/YYYY-MM-DD/timestampe-文件名
            'cover' => sprintf("{$tenantNo}/cover/{$day}/%s-%s", $time, $filename),
            'summary' => sprintf("{$tenantNo}/summary/{$day}/%s-%s", $time, $filename),
            'description' => sprintf("{$tenantNo}/description/{$day}/%s-%s", $time, $filename),
            default => throw new \Exception('未知的使用类型：' . $usageType),
        };

        $savePath = trim($savePath, '/');

        $resp = \FresnsCmdWord::plugin('FileStorage')->upload([
            'type' => $type,
            'usageType' => $usageType,
            'savePath' => $savePath,
            'file' => $file,
            'filename' => $filename,
            'mime' => $mime,
            'size' => $size,
        ]);

        if ($resp->isErrorResponse()) {
            return $this->fail($resp->getMessage(), $resp->getCode(), $resp->getData());
        }

        $uploadTokenInfo = null;
        if (empty($file)) {
            $driver = FileUtility::getFileStorageDriver();
            $uploadTokenInfo = match ($driver) {
                \Plugins\FileStorage\Utilities\CosUtility::DISK_KEY => \Plugins\FileStorage\Utilities\CosUtility::getKeyAndCredentials($savePath),
                \Plugins\FileStorage\Utilities\OssUtility::DISK_KEY => null,
                default => null,
            };
        }

        return $this->success([
            'fileinfo' => $resp->getData(),
            'uploadTokenInfo' => $uploadTokenInfo,
        ]);
    }

    public function fileUploadFinished()
    {
        request()->validate([
            'id' => ['nullable', 'integer'],
            'path' => ['nullable', 'string'],
            'usages' => ['nullable', 'array'],
            'temporary' => ['nullable', 'array'],
        ]);

        $fileId = request('id');
        $filepath = request('path');
        $usages = request('usages', []);
        $temporary = request('temporary', false);


        $model = File::where('path', $filepath)->first();
        if (!$model) {
            return $this->fail("更新上传结果失败, 未找到文件信息: fileId: $fileId, filepath: $filepath");
        }

        $model->update([
            'is_uploaded' => true,
        ]);

        $resp = \FresnsCmdWord::plugin('FileStorage')->getFileInfo([
            'fileId' => $fileId,
            'filepath' => $filepath,
            'temporary' => $temporary,
        ]);

        return $this->success($resp->getData());
    }

    public function fileDownload(string $filename)
    {
        \request()->validate([
            'path' => ['required', 'string'],
            'action' => ['nullable', 'string'],
            'extension' => ['nullable', 'string'],
        ]);

        $action = \request('action', 'download');
        $path = \request('path');

        FileUtility::initConfig();
        $response = FileUtility::handleFileWithAction($action, $path);

        throw_if(!$response, '未找到文件');

        return $response;
    }

    public function fileView(string $filename)
    {
        \request()->validate([
            'path' => ['required', 'string'],
            'disk' => ['nullable', 'string'],
            'action' => ['nullable', 'string'],
            'extension' => ['nullable', 'string'],
        ]);

        $action = \request('action', 'view');
        $path = \request('path');

        $response = FileUtility::handleFileWithAction($action, $path);

        throw_if(!$response, '未找到文件');

        $mime = FileUtility::handleFileWithAction('mime', $path);

        return \response($response)->header('content-type', $mime);
    }
}
