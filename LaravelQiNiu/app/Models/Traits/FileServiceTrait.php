<?php

namespace Plugins\LaravelQiNiu\Models\Traits;

use Plugins\LaravelQiNiu\Models\File;
use ZhenMu\Support\Utils\LaravelCache;

/**
 * @mixin File
 */
trait FileServiceTrait
{
    public static function findById(?int $fileId): ?File
    {
        $cacheKey = File::CACHE_DETAIL_PREFIX . $fileId;

        return LaravelCache::remember($cacheKey, function () use ($fileId) {
            if (!$fileId) {
                return null;
            }

            return File::find($fileId);
        });
    }

    public static function addFile(array $data)
    {
        $file = File::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'mime' => $data['mime'],
            'path' => $data['path'],
            'url' => $data['url'],
            'origin_path' => $data['origin_path'] ?? null,
            'is_physical_delete' => false,
        ]);

        File::forgetCache($file->id);
        
        return $file;
    }

    public static function deleteFile(?int $fileId)
    {
        $file = File::findById($fileId);
        if (!$file) {
            return false;
        }

        File::forgetCache($file->id);
        
        return $file->delete();
    }

    public static function forgetCache(int $fileId)
    {
        $cacheKey = File::CACHE_DETAIL_PREFIX . $fileId;
        
        LaravelCache::forget($cacheKey);
    }
}
