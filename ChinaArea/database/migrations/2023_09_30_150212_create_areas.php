<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // phpmyadmin import csv and export sql file
        // @see https://github.com/xiangyuecn/AreaCity-JsSpider-StatsGov/blob/master/src/%E9%87%87%E9%9B%86%E5%88%B0%E7%9A%84%E6%95%B0%E6%8D%AE/ok_data_level3.csv
        if (!Schema::hasTable('areas')) {
            $file = dirname(__FILE__, 2) . '/seeders/areas.sql';
            DB::unprepared(file_get_contents($file));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
