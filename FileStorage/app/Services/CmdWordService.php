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
use Plugins\MarketManager\Utils\LaravelCache;

class CmdWordService
{
    use CmdWordResponseTrait;

    public function initConfig()
    {
        FileUtility::initConfig();

        return $this->success();
    }

    public function upload(array $wordBody)
    {
        $usageType = $wordBody['usageType'];
        /** @var UploadedFile|null $file */
        $file = $wordBody['file'] ?? null;
        $filename = $wordBody['filename'] ?? null;
        $mime = $wordBody['mime'] ?? null;
        $size = $wordBody['size'] ?? null;
        $options = $wordBody['options'] ?? [];
        if (!$file && !$filename) {
            return $this->failure(400, "请提供要上传的文件信息");
        }

        if ($file && !$file instanceof UploadedFile) {
            return $this->failure(400, "文件类型不正确");
        }

        if ($file) {
            $filename = $file?->getClientOriginalName();
            $mime = $file->getClientMimeType();
            $size = $file->getSize();
            $fileOrFilename = $file;
        } else {
            $fileOrFilename = $filename;
        }

        $type = $wordBody['type'] ?? FileUtility::getFileTypeByMimeOrFilename($mime, $filename);;

        if (!empty($wordBody['savePath'] ?? null)) {
            $savePath = $wordBody['savePath'];
            $isCustomSavePath = true;
        } else {
            $isCustomSavePath = false;
            $savePath = FileUtility::fresnsFileStoragePath($type, $usageType);
        }

        if (empty($savePath)) {
            return $this->failure(400, "保存路径不能为空");
        }

        FileUtility::initConfig();
        $fileMetaInfo = FileUtility::saveToDiskAndGetFileInfo($fileOrFilename, $savePath, $isCustomSavePath, $options, $mime, $size);
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
        $isCustomSavePath = $wordBody['isCustomSavePath'];
        $options = $wordBody['options'] ?? [];

        if (!$file instanceof UploadedFile) {
            return $this->failure(400, "文件类型不正确");
        }

        if (empty($savePath)) {
            return $this->failure(400, "保存路径不能为空");
        }

        FileUtility::initConfig();
        $fileMetaInfo = FileUtility::saveToDiskAndGetFileInfo($file, $savePath, $isCustomSavePath, $options);
        $file = FileUtility::create($fileMetaInfo);

        return $this->success($file->getFileInfo());
    }

    public function getFileInfo(array $wordBody)
    {
        $fileId = $wordBody['fileId'];
        $filepath = $wordBody['filepath'];
        $temporary = $wordBody['temporary'] ?? false;

        $fileInfo = FileUtility::getFileInfo($fileId, $filepath, $temporary);

        return $this->success([
            'fileinfo' => $fileInfo,
        ]);
    }

    public function getFileUrl(array $wordBody)
    {
        $fileId = $wordBody['fileId'] ?? null;
        $filepath = $wordBody['filepath'] ?? null;

        $cacheKey = sprintf('file_url:file_id_%s:file_path_%s', $fileId, $filepath);
        $url = LaravelCache::remember($cacheKey, function () use ($fileId, $filepath) {
            $url = FileUtility::getFileUrl($fileId, $filepath);
            return $url;
        });

        return $this->success([
            'file_url' => $url,
        ]);
    }

    public function getFileTemporaryUrl(array $wordBody)
    {
        $fileId = $wordBody['fileId'] ?? null;
        $filepath = $wordBody['filepath'] ?? null;

        $cacheKey = sprintf('file_temporary_url:file_id_%s:file_path_%s', $fileId, $filepath);
        $url = LaravelCache::remember($cacheKey, function ($cacheTime) use ($fileId, $filepath) {
            $url = FileUtility::getFileTemporaryUrl($fileId, $filepath, $cacheTime);
            return $url;
        });

        return $this->success([
            'file_url' => $url,
        ]);
    }
}
