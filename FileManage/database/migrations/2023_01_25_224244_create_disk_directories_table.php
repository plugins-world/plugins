<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disk_directories', function (Blueprint $table) {
            $table->id();
            $table->string('ddid')->nullable()->comment('磁盘目录编号');
            $table->string('name')->nullable()->comment('目录名');
            $table->string('dirpath')->comment('目录路径');
            $table->string('root_path')->comment('目录根路径');
            $table->string('visit_url')->comment('访问链接');
            $table->string('is_display')->default(true)->comment('是否显示');
            $table->string('order')->default(0)->comment('排列顺序');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disk_directories');
    }
};
