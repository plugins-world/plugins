<?php

namespace Plugins\ChinaArea\Models;

class Area extends Model
{
    public static function getAreas(string $pid = '0', int $deep = 0)
    {
        return Area::query()->where([
            'pid' => $pid,
            'deep' => $deep,
        ])->get();
    }
}
