<?php

namespace Plugins\FileManage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiskDirectoryFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function file()
    {
        return $this->hasOne(File::class, 'fid', 'fid');
    }
}
