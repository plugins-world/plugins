<?php

namespace Plugins\FileManage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ZhenMu\Support\Utils\Uuid;

class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function add(array $data, ?DiskDirectory $diskDirectory = null)
    {
        $fileItem = [];
        $fileItem['alias'] = $data['basename'];
        $fileItem['filename'] = $data['filename'];
        $fileItem['extension'] = $data['extension'];
        $fileItem['pathname'] = $data['pathname'];
        $fileItem['relative_pathname'] = $data['relative_pathname'];
        $fileItem['realpath'] = $data['realpath'];
        $fileItem['relative_realpath'] = $data['relative_realpath'];
        $fileItem['file_type'] = $data['file_type'];
        $fileItem['mime_type'] = $data['mime_type'];
        $fileItem['size'] = $data['size'];
        $fileItem['size_desc'] = $data['size_desc'];
        $fileItem['ctime'] = $data['ctime'];
        $fileItem['mtime'] = $data['mtime'];
        $fileItem['atime'] = $data['atime'];
        $fileItem['link_target'] = $data['link_target'];
        $fileItem['url'] = $data['url'];
        $fileItem['preview_url'] = $data['preview_url'];

        $fileModel = File::where('realpath', $fileItem['realpath'])->first();

        if (!$fileModel) {
            // 首次创建，标记为未转码
            $fileItem['is_transcoded'] = false;

            $fileModel = File::create($fileItem);
        } else {
            $fileModel->update($fileItem);
        }

        if (!$fileModel->fid) {
            $fileModel->update([
                'fid' => Uuid::uuid(),
            ]);
        }

        if ($diskDirectory) {
            DiskDirectoryFile::firstOrCreate([
                'ddid' => $diskDirectory->ddid,
                'fid' => $fileModel->fid,
            ]);
        }

        return $fileModel;
    }
}
