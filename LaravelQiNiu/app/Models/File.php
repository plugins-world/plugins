<?php

namespace Plugins\LaravelQiNiu\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    const CACHE_DETAIL_PREFIX = 'file_';

    const TYPE_UNKNOWN = 1;
    const TYPE_IMAGE = 2;
    const TYPE_VIDEO = 3;
    const TYPE_MAP = [
        File::TYPE_UNKNOWN => '未知类型',
        File::TYPE_IMAGE => '图片',
        File::TYPE_VIDEO => '视频',
    ];

    use HasFactory;
    use SoftDeletes;
    use Traits\FileServiceTrait;

    protected $guarded = [];

    public function getTypeDescAttribute()
    {
        return File::TYPE_MAP[$this->type] ?? "未知类型 {$this->type}";
    }

    public function getDetail()
    {
        return [
            'file_id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'mime' => $this->mime,
            'path' => $this->path,
            'url' => $this->url,
            'origin_path' => $this->origin_path,
            'is_physical_delete' => $this->is_physical_delete,
        ];
    }
}
