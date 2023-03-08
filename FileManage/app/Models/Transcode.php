<?php

namespace Plugins\FileManage\Models;

use ZhenMu\Support\Utils\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transcode extends Model
{
    use HasFactory;

    const STATUS_WAITING = 'WAITING';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_FINISHED = 'FINISHED';
    const STATUS_FAILED = 'FAILED';

    protected $guarded = [];

    public static function addTask(array $data)
    {
        $transcode = [];
        $transcode['tid'] = Uuid::uuid();
        $transcode['ddid'] = $data['ddid'];
        $transcode['fid'] = $data['fid'];
        $transcode['type'] = $data['type'];
        $transcode['origin_filepath'] = $data['origin_filepath'];
        $transcode['transcode_filepath'] = $data['transcode_filepath'];
        $transcode['transcode_finish_filepath'] = $data['transcode_finish_filepath'];
        $transcode['thumb_image'] = $data['thumb_image'];
        $transcode['is_clean_origin_file'] = $data['is_clean_origin_file'];
        $transcode['start_time'] = $data['start_time'];
        $transcode['end_time'] = $data['end_time'];
        $transcode['status'] = $data['status'] ?? Transcode::STATUS_WAITING;
        $transcode['step'] = $data['step'] ?? 'add_task';
        $transcode['step_message'] = $data['step_message'] ?? '添加转码任务';
        $transcode['is_notify'] = $data['is_notify'] ?? false;
        $transcode['notify_result'] = $data['notify_result'] ?? null;

        return Transcode::create($transcode);
    }

    public static function updateTask(array $data)
    {
        $attributes['tid'] = $data['tid'] ?? null;
        
        $transcode = Transcode::where('tid', $attributes['tid'])->first();
        if (!$transcode) {
            return null;
        }

        $attributes['transcode_filepath'] = $data['transcode_filepath'] ?? null;
        $attributes['transcode_finish_filepath'] = $data['transcode_finish_filepath'] ?? null;
        $attributes['thumb_image'] = $data['thumb_image'] ?? null;
        $attributes['is_clean_origin_file'] = $data['is_clean_origin_file'] ?? null;
        $attributes['start_time'] = $data['start_time'] ?? null;
        $attributes['end_time'] = $data['end_time'] ?? null;
        $attributes['status'] = $data['status'] ?? null;
        $attributes['step'] = $data['step'] ?? null;
        $attributes['step_message'] = $data['step_message'] ?? null;
        $attributes['is_notify'] = $data['is_notify'] ?? null;
        $attributes['notify_result'] = $data['is_notify'] ?? null;

        $transcode->update($attributes);

        return $transcode;
    }
}
