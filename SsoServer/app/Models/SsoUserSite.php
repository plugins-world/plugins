<?php

namespace Plugins\SsoServer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsoUserSite extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expire_time' => 'datetime',
    ];
}
