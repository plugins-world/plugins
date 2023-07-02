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

    /**
        $resp = \FresnsCmdWord::plugin('FileStorage')->uploadFile([
            'file' => $uploadFile,
            'savePath' => $savePath,
        ]);
     */
    public function uploadFile($wordBody)
    {
        $file = $wordBody['file'];
        $savePath = $wordBody['savePath'];
        $options = $wordBody['options'] ?? [];

        if (! $file instanceof UploadedFile) {
            return $this->failure("文件类型不正确");
        }

        if (empty($savePath)) {
            return $this->failure("保存路径不能为空");
        }

        $fileMetaInfo = FileUtility::saveToDiskAndGetFileInfo($file, $savePath, $options);
        $file = FileUtility::create($fileMetaInfo);

        return $this->success($file->getFileInfo());
    }
}
