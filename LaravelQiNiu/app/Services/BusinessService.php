<?php

namespace Plugins\LaravelQiNiu\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Plugins\LaravelQiNiu\Models\File as FileModel;

class BusinessService
{
    protected $qiNiuService;

    public function __construct()
    {
        $this->qiNiuService = new QiNiuService();

        $this->qiNiuService = $this->qiNiuService->withCentral(db_config_central('is_central_config', false));
    }

    public static function make()
    {
        return new static();
    }

    public function getToken(array $data): array
    {
        $instance = static::make();

        $expireTime = $data['expire_time'] ?? 3600;
        $token = $instance->qiNiuService->getAdapter()?->getUploadToken(
            $data['name'],
            $expireTime
        );

        return [
            'name' => $data['name'],
            'token' => $token,
            'expire_time' => $expireTime,
        ];
    }

    public function upload(array $data): ?array
    {
        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $data['file'] ?? null;
        $path = $data['path'];

        if (!($file instanceof UploadedFile)) {
            throw new \RuntimeException("请上传文件");
        }

        $mimeType = File::mimeType($file->getRealPath());
        $type = match (true) {
            str_contains($mimeType, 'image/') => FileModel::TYPE_IMAGE,
            str_contains($mimeType, 'video/') => FileModel::TYPE_VIDEO,
            default => FileModel::TYPE_UNKNOWN,
        };

        if (function_exists('tenant') && tenant()) {
            $path = sprintf('%s%s/%s', config('tenancy.filesystem.suffix_base'), tenant('id'), $data['path']);
        }

        $service = static::make();

        $path = $service->qiNiuService->getStorage()->put($path, $file);

        $result = [
            'name' => $file->getClientOriginalName(),
            'type' => $type,
            'mime' => $mimeType,
            'path' => $path,
            'url' => $service->qiNiuService->getStorage()->url($path),
        ];

        if ($service->qiNiuService->isCentralConfig()) {
            $file = central(function () use ($result) {
                return FileModel::addFile($result);
            });
        } else {
            $file = FileModel::addFile($result);
        }

        return $file->getDetail();
    }
}
