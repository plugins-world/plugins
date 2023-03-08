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
        Schema::create('transcodes', function (Blueprint $table) {
            $table->id();
            $table->string('ddid')->index()->nullable()->comment('磁盘目录编号');
            $table->string('fid')->index()->nullable()->comment('文件编号');
            $table->string('type')->index()->comment('转码类型');
            $table->string('origin_filepath')->nullable()->comment('文件原始路径');
            $table->string('transcode_filepath')->nullable()->comment('视频转码后的路径');
            $table->string('transcode_finish_filepath')->nullable()->comment('转码完成后原文件的备份路径');
            $table->string('thumb_image')->nullable()->comment('缩略图');
            $table->string('is_clean_origin_file')->nullable()->comment('原文件是否已清理');
            $table->string('start_time')->nullable()->comment('转码开始时间');
            $table->string('end_time')->nullable()->comment('转码完成时间');
            $table->string('status')->nullable()->comment('转码状态');
            $table->string('step')->nullable()->comment('转码进度');
            $table->string('step_message')->nullable()->comment('转码进度说明');
            $table->string('is_notify')->nullable()->comment('转码完成是否通知');
            $table->string('notify_result')->nullable()->comment('通知结果');
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
        Schema::dropIfExists('transcodes');
    }
};
