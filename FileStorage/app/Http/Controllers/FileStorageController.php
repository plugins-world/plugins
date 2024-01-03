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
            'usage_type' => ['required'], // 自行通过文档进行约束说明: usage_type: avatars-头像;...
            'file' => ['required', 'file'],
        ]);

        $resp = \FresnsCmdWord::plugin('FileStorage')->upload([
            'type' => request('type'),
            'usageType' => request('usage_type'),
            'file' => request('file'),
        ]);
        $fileInfo = $resp->getData();
        $fileInfo['url'] = FileUtility::getFileTemporaryUrl(null, $fileInfo['path']);

        return $this->success($fileInfo);
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
