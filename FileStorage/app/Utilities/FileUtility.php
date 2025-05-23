<?php

namespace Plugins\FileStorage\Utilities;

use DateTime;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Plugins\FileStorage\Models\File;
use Illuminate\Support\Facades\Storage;
use Plugins\LaravelConfig\Models\Config;
use Illuminate\Filesystem\FilesystemAdapter;
use Plugins\MarketManager\Utilities\StrUtility;
use Illuminate\Support\Facades\File as FacadesFile;

class FileUtility
{
    public static function getFileStorageDriver()
    {
        $driver = Config::getValueByKey('file_storage_driver') ?? 'public';

        return $driver;
    }

    public static function initTimezone()
    {
        $timezone = Config::getValueByKey('file_storage_timezone');
        if ($timezone) {
            date_default_timezone_set($timezone);
        }
    }

    public static function initConfig()
    {
        FileUtility::initTimezone();

        $disk = FileUtility::getFileStorageDriver();

        match ($disk) {
            default => null,
            CosUtility::DISK_KEY => CosUtility::dynamicsConfig(),
            OssUtility::DISK_KEY => OssUtility::dynamicsConfig(),
        };

        return $disk;
    }

    // get file storage path
    public static function fresnsFileStoragePath(string $fileType, string $usageType): string
    {
        FileUtility::initTimezone();

        $fileTypeDir = match ($fileType) {
            File::TYPE_IMAGE => 'images',
            File::TYPE_VIDEO => 'videos',
            File::TYPE_AUDIO => 'audios',
            File::TYPE_DOCUMENT => 'documents',
            default => 'others',
        };

        $usageTypes = Str::plural($usageType);

        $usageTypeDir = "{$usageTypes}/{YYYYMM}/{DD}";

        $replaceUseTypeDir = str_replace(
            ['{YYYYMM}', '{DD}'],
            [date('Ym'), date('d')],
            $usageTypeDir
        );

        return sprintf('%s/%s', trim($fileTypeDir, '/'), trim($replaceUseTypeDir, '/'));
    }

    public static function getFileTypeByMimeOrFilename(string $mime, string $filename = '')
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $type = match (true) {
            str_contains($mime, FILE::TYPE_IMAGE) => FILE::TYPE_IMAGE,
            str_contains($mime, FILE::TYPE_VIDEO) => FILE::TYPE_VIDEO,
            str_contains($mime, FILE::TYPE_AUDIO) => FILE::TYPE_AUDIO,

            in_array($extension, File::EXTENSION_VIDEO) => FILE::TYPE_VIDEO,
            in_array($extension,  File::EXTENSION_DOCUMENT) => FILE::TYPE_DOCUMENT,

            in_array($extension,  File::EXTENSION_VOD) => File::TYPE_VOD,
            in_array($extension, FILE::EXTENSION_ZIP) => File::TYPE_ZIP,

            default => FILE::TYPE_FILE,
        };

        return $type;
    }

    public static function getFilePathInfoFromPathOrUrl(string $pathOrUrl)
    {
        $filepath = $pathOrUrl;
        if (filter_var($pathOrUrl, FILTER_VALIDATE_URL)) {
            $urlInfo = parse_url($pathOrUrl);
            $urlPath = $urlInfo['path'] ?? '';
            $filepath = ltrim($urlPath, '/');
        }

        $pathInfo = pathinfo($filepath);
        $pathInfo['origin_path'] = $filepath;

        return $pathInfo;
    }

    public static function getHumanizeSize(int $filesize)
    {
        if ($filesize >= 1073741824) {
            //转成GB
            $filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
        } elseif ($filesize >= 1048576) {
            //转成MB
            $filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
        } elseif ($filesize >= 1024) {
            //转成KB
            $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
        } else {
            //不转换直接输出
            $filesize = $filesize . ' B';
        }

        return $filesize;
    }

    public static function getStorage(): FilesystemAdapter
    {
        $disk = FileUtility::initConfig();

        if ($disk == 'local' || $disk == 'public') {
            FileUtility::buildLocalTemporaryUrls();
        }

        /** @var FilesystemAdapter */
        return Storage::disk($disk);
    }

    public static function getImageUrl($path)
    {
        $httpOrigin = request()->server('HTTP_ORIGIN');
        $finalUrl = $httpOrigin . $path;
        return $finalUrl;
    }

    public static function buildLocalTemporaryUrls()
    {
        $disk = FileUtility::initConfig();

        /** @var FilesystemAdapter */
        $storage = Storage::disk($disk);

        $storage->buildTemporaryUrlsUsing(
            function (string $path, DateTime $expiration, array $options) {
                $filename = pathinfo($path, PATHINFO_FILENAME);
                $extension = pathinfo($path, PATHINFO_EXTENSION);

                return URL::temporarySignedRoute(
                    'file.download',
                    $expiration,
                    array_merge($options, ['action' => 'view', 'filename' => $filename, 'extension' => $extension, 'path' => $path]),
                    true,
                );
            }
        );
    }

    public static function handleFileWithAction($action, $path)
    {
        if (empty($path)) {
            return null;
        }

        $disk = FileUtility::initConfig();

        /** @var FilesystemAdapter */
        $storage = Storage::disk($disk);

        if (!$storage->has($path)) {
            return null;
        }

        $filename = pathinfo($path, PATHINFO_BASENAME);

        return match ($action) {
            default => null,
            'get' => $storage->get($path),
            'download' => $storage->download($path, $filename),
            'view' => $storage->download($path, null, ['Content-Disposition' => sprintf('inline; filename="%s"', $filename)]),
            'mime' => $storage->mimeType($path),
        };
    }

    public static function saveToDiskAndGetFileInfo(UploadedFile|string $fileOrFilename, $savePath, $isCustomSavePath = false, $options = [], $mime = null, $size = null)
    {
        $storage = FileUtility::getStorage();
        $disk = FileUtility::getFileStorageDriver();

        $file = null;
        $filename = $fileOrFilename instanceof \Illuminate\Http\UploadedFile ? $fileOrFilename?->getClientOriginalName() : $fileOrFilename;
        $isBackendUpload = $fileOrFilename instanceof \Illuminate\Http\UploadedFile;
        $isBackendUpload = false;
        if ($isBackendUpload) {
            $file = $fileOrFilename;
        }

        $md5 = null;
        $sha = null;
        $sha_type = null;
        if (is_file($file?->path())) {
            $md5 = md5_file($file->path());
            $sha = sha1_file($file->path());
            $sha_type = 'sha1';
        }

        $fileModel = File::where('md5', $md5)->first();

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        if ($isCustomSavePath) {
            $fileSaveName = basename($savePath);
            $savePath = dirname($savePath);
        } else {
            $randomBasename = Str::random(40);
            $fileSaveName = $randomBasename . "." . $extension;
            if ($fileModel?->path) {
                $fileSaveName = basename($fileModel->path);
            }
        }

        $publicDiskPrefix = "";
        $relativePath = "$savePath/{$fileSaveName}";
        $absolutePath = $relativePath;

        if ($disk == 'local' || $disk == 'public') {
            $absolutePath = storage_path("app/public/{$relativePath}");
            $publicDiskPrefix = storage_path("app/public/");

            if (!$storage->has($relativePath)) {
                $dir = $publicDiskPrefix . $savePath;
                FacadesFile::ensureDirectoryExists($dir);
            }
        }

        try {
            if ($isBackendUpload && !$storage->has($relativePath)) {
                $storage->putFileAs($savePath, $file, $fileSaveName, $options);
            }
        } catch (\Throwable $e) {
            if ($e instanceof \OSS\Core\OssException) {
                $property = new \ReflectionProperty($e, 'details');
                $property->setAccessible(true);
                $messageDataArray = $property->getValue($e);
                $property->setAccessible(false);

                $code = $e->getErrorCode() ?: $e->getHTTPStatus();
                $message = $e->getDetails() ?: $e->getErrorMessage() ?: $e->getRequestId();
                if ($messageDataArray) {
                    $message = sprintf("JSON.parse('%s');", json_encode($messageDataArray, \JSON_UNESCAPED_SLASHES));
                }

                throw new \RuntimeException($message, $code);
            }

            throw $e;
        }

        $data['is_uploaded'] = $isBackendUpload ? true : false;
        $data['name'] = $filename;
        $data['type'] = FileUtility::getFileTypeByMimeOrFilename($mime, $filename);
        $data['mime'] = $mime;
        $data['extension'] = $extension;
        $data['size'] = $size;
        $data['size_desc'] = FileUtility::getHumanizeSize($size);
        $data['md5'] = $md5;
        $data['sha'] = $sha;
        $data['sha_type'] = $sha_type;
        $data['path'] = $relativePath;
        $data['absolute_path'] = ltrim(str_replace(base_path(), '', $absolutePath), '/');

        switch ($data['type']) {
            case 'image':
                $imageInfo = null;
                if ($imageInfo) {
                    $imageInfo = getimagesize($file->path());
                }

                if ($imageInfo) {
                    $data['image_width'] = $imageInfo[0];
                    $data['image_height'] = $imageInfo[1];
                    $data['bits'] = $imageInfo['bits'];
                    $data['channels'] = $imageInfo['channels'] ?? null;
                    $data['mime'] = $imageInfo['mime'];
                }
                break;
        }

        return $data;
    }

    public static function getFileMeta(array $fileInfo)
    {
        // 必须提供的字段
        $type = $fileInfo['type'];
        $name = $fileInfo['name'];
        $mime = $fileInfo['mime'];
        $extension = $fileInfo['extension'];
        $size = $fileInfo['size'];

        // 文件元数据信息
        $fileData['type'] = $type;
        $fileData['name'] = $name;
        $fileData['mime'] = $mime;
        $fileData['extension'] = $extension;
        $fileData['size'] = $size;
        $fileData['md5'] = $fileInfo['md5'] ?? null;
        $fileData['sha'] = $fileInfo['sha'] ?? null;
        $fileData['sha_type'] = $fileInfo['sha_type'] ?? null;
        $fileData['path'] = $fileInfo['path'] ?? null;
        $fileData['url'] = $fileInfo['url'] ?? null;
        $fileData['image_width'] = $fileInfo['image_width'] ?? null;
        $fileData['image_height'] = $fileInfo['image_height'] ?? null;
        $fileData['audio_time'] = $fileInfo['audio_time'] ?? null;
        $fileData['video_time'] = $fileInfo['video_time'] ?? null;
        $fileData['video_poster_path'] = $fileInfo['video_poster_path'] ?? null;
        $fileData['more_json'] = $fileInfo['more_json'] ?? null;
        $fileData['transcoding_state'] = $fileInfo['transcoding_state'] ?? null;
        $fileData['transcoding_reason'] = $fileInfo['transcoding_reason'] ?? null;
        $fileData['original_path'] = $fileInfo['original_path'] ?? null;
        $fileData['is_physical_delete'] = $fileInfo['is_physical_delete'] ?? false;
        $fileData['table_id'] = $fileInfo['table_id'] ?? null;
        $fileData['usage_type'] = $fileInfo['usage_type'] ?? null;

        return $fileData;
    }

    public static function create($params): File
    {
        $file = File::where('md5', $params['md5'])->first();

        $data = collect($params)->only([
            'is_uploaded',
            'type',
            'name',
            'mime',
            'extension',
            'size',
            'md5',
            'sha',
            'sha_type',
            'path',
            'image_width',
            'image_height',
            'audio_time',
            'video_time',
            'video_poster_path',
            'more_json',
            'transcoding_state',
            'transcoding_reason',
            'original_path',
            'is_physical_delete',
        ])->all();

        $file = File::updateOrCreate([
            'md5' => $data['md5'],
            'path' => $data['path'],
        ], $data);

        return $file;
    }

    public static function getFileInfo(?string $fileId = null, ?string $filepath = null, $temporary = false)
    {
        if (!$fileId && !$filepath) {
            return null;
        }

        if ($filepath && filter_var($filepath, FILTER_VALIDATE_URL)) {
            $fileInfo = [];
            $fileInfo['name'] = pathinfo($filepath, PATHINFO_BASENAME);
            $fileInfo['path'] = parse_url($filepath, PATHINFO_BASENAME)['path'] ?? null;
            $fileInfo['url'] = $filepath;
            return $fileInfo;
        }

        if ($fileId) {
            $file = File::where('id', $fileId)->first();
        } else if ($filepath) {
            $file = File::where('path', $filepath)->first();
        } else {
            $file = null;
        }

        if (!$file) {
            return null;
        }

        $fileInfo = $file->getFileInfo($temporary);

        return $fileInfo;
    }

    public static function getFileUrl(?string $fileId = null, ?string $filepath = null)
    {
        $fileInfo = FileUtility::getFileInfo($fileId, $filepath);
        if (!$fileInfo) {
            return null;
        }

        if (empty($fileInfo['fid'])) {
            $url = $fileInfo['url'];
        } else {
            $url = FileUtility::getStorage()->url($fileInfo['path']);
        }

        return StrUtility::qualifyUrl($url);
    }

    public static function getFileTemporaryUrl(?string $fileId = null, ?string $filepath = null, $cacheTime = null)
    {
        $fileInfo = FileUtility::getFileInfo($fileId, $filepath);
        if (!$fileInfo) {
            return null;
        }

        if (empty($fileInfo['fid'])) {
            $url = $fileInfo['url'];
        } else {
            $driver = FileUtility::getFileStorageDriver();

            if ($cacheTime) {
                $expiresMinutes = $cacheTime;
            } else {
                $expiresMinutes = match ($driver) {
                    default => now()->addMinutes(20),
                    CosUtility::DISK_KEY => now()->addMinutes(20),
                    OssUtility::DISK_KEY => 20 * 60,
                };
            }

            $url = FileUtility::getStorage()->temporaryUrl($fileInfo['path'], $expiresMinutes);
        }

        return StrUtility::qualifyUrl($url);
    }
}
