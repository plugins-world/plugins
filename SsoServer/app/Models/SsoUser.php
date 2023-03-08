<?php

namespace Plugins\SsoServer\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SsoUser extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $guarded = [];
}
