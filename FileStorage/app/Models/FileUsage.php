<?php

namespace Plugins\FileStorage\Models;

class FileUsage extends \Plugins\MarketManager\Models\Model
{
    public function scopeFileType($query, int $type)
    {
        return $query->where('file_type', $type);
    }

    public function scopeType($query, int $type)
    {
        return $query->where('object_type', $type);
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
