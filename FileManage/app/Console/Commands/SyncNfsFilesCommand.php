<?php

namespace Plugins\FileManage\Console\Commands;

use Illuminate\Console\Command;
use MouYong\LaravelConfig\Models\Config;
use Plugins\FileManage\Models\DiskDirectory;
use Plugins\FileManage\Models\File;

class SyncNfsFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nfs:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步 nfs 的文件信息';

    /**
     * @var \Mimey\MimeTypes
     */
    protected $mimes;

    protected $config = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(0);

        $diskDirectories = DiskDirectory::all();

        $this->mimes = new \Mimey\MimeTypes;

        $this->config = Config::getValueByKeys(['document_preview_url', 'file_preview_url', 'exclude_files']);

        foreach ($diskDirectories as $diskDirectory) {
            if (!file_exists($diskDirectory->dirpath)) {
                continue;
            }

            $cacheKey = implode(':', explode('/', $diskDirectory->dirpath));

            $this->sync($diskDirectory, $diskDirectory->visit_url, $this->config['exclude_files']);
        }

        return Command::SUCCESS;
    }

    public function sync($diskDirectory, $visitUrl, $exclude_files = [])
    {
        $fileIterator = $this->getFile($diskDirectory, $exclude_files, $visitUrl);
        foreach ($fileIterator as $fileItem) {
            $fileItem;

            File::add($fileItem, $diskDirectory);
        }
    }

    public function getFile($diskDirectory, $exclude_files, $visitUrl)
    {
        $dir_iterator = new \RecursiveDirectoryIterator($diskDirectory->dirpath, \FilesystemIterator::FOLLOW_SYMLINKS);
        $iterator = new \RecursiveIteratorIterator($dir_iterator);
        foreach ($iterator as $file) {
            if ($file->getFilename() == '.' || $file->getFilename() == '..') {
                continue;
            }

            if (in_array($file->getExtension(), $exclude_files)) {
                continue;
            }

            $fileItem = [];
            $fileItem['basename'] = $file->getFilename();
            $fileItem['extension'] = strtolower($file->getExtension());
            $fileItem['filename'] = str_replace(".{$fileItem['extension']}", '', $fileItem['basename']);
            $fileItem['pathname'] = $file->getPath();
            $fileItem['relative_pathname'] = str_replace($diskDirectory->root_path, '', $fileItem['pathname']);
            $fileItem['realpath'] = $file->getRealpath();
            $fileItem['relative_realpath'] = str_replace($diskDirectory->root_path, '', $fileItem['realpath']);
            $fileItem['file_type'] = $file->getType();
            $fileItem['mime_type'] = $this->mimes->getMimeType($fileItem['extension']) ?? 'text/plain';
            $fileItem['size'] = $file->getSize();
            $fileItem['size_desc'] = $fileItem['size'] . 'B';
            $fileItem['ctime'] = $file->getCTime();
            $fileItem['mtime'] = $file->getMTime();
            $fileItem['atime'] = $file->getATime();
            $fileItem['link_target'] = $file->isLink() ? $file->getLinkTarget() : null;
            $fileItem['url'] = $visitUrl . $fileItem['relative_realpath'];
            $fileItem['preview_url'] = $fileItem['url'];

            $fileItem['size_desc'] = $this->size_format($fileItem['size']);
            $fileItem['ctime'] = date('Y-m-d H:i:s', $fileItem['ctime']);
            $fileItem['mtime'] = date('Y-m-d H:i:s', $fileItem['mtime']);
            $fileItem['atime'] = date('Y-m-d H:i:s', $fileItem['atime']);

            $fileItem['file_type'] = match (true) {
                default => 'file',
                str_contains($fileItem['mime_type'], 'image') => 'image',
                str_contains($fileItem['mime_type'], 'video') => 'video',
                str_contains($fileItem['mime_type'], 'audio') => 'audio',

                in_array($fileItem['extension'], [
                    'ts',
                ]) => 'video',

                in_array($fileItem['extension'], [
                    'm3u8',
                ]) => 'vod',

                in_array($fileItem['extension'], [
                    'txt',
                    'wps', 'wpt', 'et', 'dps',
                    'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'dot', 'pot', 'xlt', 'vsd', 'pdf', 'rtf',
                    'odt', 'ods', 'odp', 'odg', 'odf',
                ]) => 'document',

                in_array($fileItem['extension'], [
                    'zip', 'rar', '7z', 'tar', 'gz',
                    'bz2', 'cab', 'lz',
                ]) => 'zip',
            };

            if ($fileItem['file_type'] == 'document') {
                $fileItem['preview_url'] = str_replace('URL', '', $this->config['document_preview_url']) . $fileItem['url'];
            }

            if (in_array($fileItem['file_type'], ['image', 'zip'])) {
                $fileItem['preview_url'] = str_replace('URL', '', $this->config['file_preview_url']) . urlencode(base64_encode($fileItem['url']));
            }

            yield $fileItem;
        }
    }

    public function size_format($filesize)
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
}
