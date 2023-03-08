<?php

namespace Plugins\LaravelLocalStorage\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Plugins\LaravelLocalStorage\Models\File as FileModel;

class BusinessService
{
    public static function make()
    {
        return new static();
    }

    public function getStorage($disk = null)
    {
        return Storage::disk($disk);
    }
    
    public function upload(array $data): ?array
    {
        /** @var \Illuminate\Http\UploadedFile|string $file */
        $file = $data['file'] ?? null;
        $path = $data['path'];

        if (!$file) {
            throw new \RuntimeException("请上传文件");
        }

        $finfo = new \finfo(FILEINFO_MIME);
        if (is_string($file)) {
            $mimeType = $finfo->buffer($file);
        } else {
            $mimeType = $finfo->file($file);
        }

        $type = match (true) {
            str_contains($mimeType, 'image/') => FileModel::TYPE_IMAGE,
            str_contains($mimeType, 'video/') => FileModel::TYPE_VIDEO,
            default => FileModel::TYPE_UNKNOWN,
        };

        if (function_exists('tenant') && tenant()) {
            $path = sprintf('public/%s', $path);
        }

        $putResult = $this->getStorage()->put($path, $file);
        if (!$putResult) {
            info('保存文件失败', [
                'path' => $path,
                'data_path' => $data['path'],
            ]);
        }

        if (!is_string($file)) {
            $path = $putResult;
        }

        $result = [
            'name' => is_string($file) ? basename($path) : $file->getClientOriginalName(),
            'type' => $type,
            'mime' => $mimeType,
            'path' => $path,
            'url' => \URL::tenantFile(str_replace('public/', '', $path)),
        ];

        $file = central(function () use ($result) {
            return FileModel::addFile($result);
        });

        return $file->getDetail();
    }
}
