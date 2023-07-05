<?php

namespace Plugins\FileStorage\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Plugins\MarketManager\Models\Traits\FsidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;
    use SoftDeletes;
    use FsidTrait;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}