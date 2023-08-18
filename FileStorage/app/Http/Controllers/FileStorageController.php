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
        \request()->validate([
            'type' => ['required', 'string', Rule::in(array_keys(File::TYPE_MAP))],
            'file' => ['required', 'file'],
            'disk' => ['nullable', 'string'],
        ]);

        $type = \request('type');
        $uploadFile = \request('file');
        $savePath = FileUtility::fresnsFileStoragePath($type, 'uploads');

        FileUtility::initConfig();
        /** @var \Fresns\CmdWordManager\CmdWordResponse $resp */
        $resp = \FresnsCmdWord::plugin('FileStorage')->uploadFile([
            'file' => $uploadFile,
            'savePath' => $savePath,
        ]);

        $data = $resp->getData();
        $data['url'] = FileUtility::getStorage()->temporaryUrl($data['path'], now()->addHour());

        return $this->success($data);
    }

    public function fileDownload()
    {
        \request()->validate([
            'path' => ['required', 'string'],
            'action' => ['nullable', 'string'],
        ]);

        $action = \request('action', 'view');
        $path = \request('path');

        FileUtility::initConfig();
        $response = FileUtility::handleFileWithAction($action, $path);

        throw_if(!$response, '未找到文件');

        return $response;
    }

    public function fileView()
    {
        \request()->validate([
            'path' => ['required', 'string'],
            'disk' => ['nullable', 'string'],
            'action' => ['nullable', 'string'],
        ]);

        $action = \request('action', 'download');
        $path = \request('path');

        $response = FileUtility::handleFileWithAction($action, $path);

        throw_if(!$response, '未找到文件');

        $mime = FileUtility::handleFileWithAction('mime', $path);
        
        return \response($response)->header('content-type', $mime);
    }
}
