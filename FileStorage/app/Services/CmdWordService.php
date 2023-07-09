<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\FileStorage\Services;

use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;
use Illuminate\Http\UploadedFile;
use Plugins\FileStorage\Utilities\FileUtility;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function upload(array $wordBody)
    {
        $type = $wordBody['type'];
        $usageType = $wordBody['usageType'];
        $file = $wordBody['file'];
        $disk = $wordBody['disk'];


        $savePath = FileUtility::fresnsFileStoragePath($type, $usageType);
        $options = ['disk' => $disk];

        if (!$file instanceof UploadedFile) {
            return $this->failure("文件类型不正确");
        }

        if (empty($savePath)) {
            return $this->failure("保存路径不能为空");
        }

        FileUtility::initConfig($options['disk'] ?? null);
        $fileMetaInfo = FileUtility::saveToDiskAndGetFileInfo($file, $savePath, $options);
        $file = FileUtility::create($fileMetaInfo);

        return $this->success($file->getFileInfo());
    }

    public function fresnsFileStoragePath(array $wordBody)
    {
        $type = $wordBody['type'];
        $usageType = $wordBody['usageType'];

        $path = FileUtility::fresnsFileStoragePath($type, $usageType);

        return $this->success([
            'path' => $path,
        ]);
    }

    public function uploadFile(array $wordBody)
    {
        $file = $wordBody['file'];
        $savePath = $wordBody['savePath'];
        $options = $wordBody['options'] ?? [];

        if (!$file instanceof UploadedFile) {
            return $this->failure("文件类型不正确");
        }

        if (empty($savePath)) {
            return $this->failure("保存路径不能为空");
        }

        FileUtility::initConfig($options['disk'] ?? null);
        $fileMetaInfo = FileUtility::saveToDiskAndGetFileInfo($file, $savePath, $options);
        $file = FileUtility::create($fileMetaInfo);

        return $this->success($file->getFileInfo());
    }

    public function getFileInfo(array $wordBody)
    {
        $fileId = $wordBody['fileId'];
        $filepath = $wordBody['filepath'];

        $fileInfo = FileUtility::getFileInfo($fileId, $filepath);

        return $this->success([
            'fileinfo' => $fileInfo,
        ]);
    }

    public function getFileUrl(array $wordBody)
    {
        $fileId = $wordBody['fileId'];
        $filepath = $wordBody['filepath'];
        $disk = $wordBody['disk'];

        FileUtility::initConfig($disk ?? null);
        $url = FileUtility::getFileUrl($fileId, $filepath, $disk);

        return $this->success([
            'file_url' => $url,
        ]);
    }
}
