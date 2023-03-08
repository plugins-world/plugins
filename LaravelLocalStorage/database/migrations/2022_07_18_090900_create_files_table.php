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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('文件名');
            $table->unsignedTinyInteger('type')->comment('文件类型');
            $table->string('mime')->comment('文件 mime');
            $table->string('path')->comment('文件 path');
            $table->string('url')->comment('文件 url');
            $table->string('origin_path')->nullable()->comment('文件 origin_path');
            $table->string('is_physical_delete')->default(0)->comment('是否删除真实的物理文件');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
