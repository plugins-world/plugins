<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('fid')->comment('对外公开编号');
            $table->string('type')->comment('文件类型');
            $table->string('name')->comment('文件名称');
            $table->string('mime')->comment('媒体类型');
            $table->string('extension')->comment('后缀');
            $table->string('size')->nullable()->comment('文件大小');
            $table->string('md5')->nullable()->comment('文件md5');
            $table->string('sha')->nullable()->comment('文件sha');
            $table->string('sha_type')->nullable()->comment('文件sha');
            $table->string('path')->comment('文件路径');
            $table->string('image_width')->nullable()->comment('图片宽度');
            $table->string('image_height')->nullable()->comment('图片高度');
            $table->string('audio_time')->nullable()->comment('音频时长');
            $table->string('video_time')->nullable()->comment('视频时长');
            $table->string('video_poster_path')->nullable()->comment('视频封面图路径');
            $table->json('more_json')->nullable()->comment('更多信息');
            $table->string('transcoding_state')->nullable()->comment('转码状态');
            $table->string('transcoding_reason')->nullable()->comment('转码失败原因');
            $table->string('original_path')->nullable()->comment('原始文件路径');
            $table->boolean('is_physical_delete')->default(0)->comment('物理删除状态');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('file_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id')->comment('文件编码');
            $table->string('file_type')->comment('文件类型');
            $table->string('usage_type')->comment('使用类型');
            $table->string('table_name')->comment('来源表');
            $table->string('table_column')->comment('来源字段');
            $table->string('table_id')->comment('来源表ID');
            $table->string('table_value')->comment('来源表值');
            $table->integer('rating')->default(0)->comment('排序');
            $table->integer('remark')->nullable()->comment('排序');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('file_downloads', function (Blueprint $table) {
            $table->id();
            $table->string('file_id')->comment('文件 ID');
            $table->string('file_type')->comment('文件类型');
            $table->string('account_id')->comment('下载者账号 ID');
            $table->string('user_id')->nullable()->comment('下载者用户 ID');
            $table->string('plugin_fskey')->nullable()->comment('下载者插件');
            $table->string('object_type')->comment('下载来源类型');
            $table->string('object_id')->comment('来源目标主键 ID');
            $table->datetime('download_time')->comment('下载时间');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
        Schema::dropIfExists('file_usages');
        Schema::dropIfExists('file_downloads');
    }
};
