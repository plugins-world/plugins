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
            $table->string('fid')->index()->nullable()->comment('文件编号');
            $table->string('alias')->index()->comment('文件别名');
            $table->string('extension')->nullable()->comment('文件后缀');
            $table->string('filename')->index()->comment('文件名');
            $table->string('pathname')->index()->comment('文件父路径');
            $table->string('relative_pathname')->comment('文件相对父路径');
            $table->string('realpath')->index()->comment('文件真实路径');
            $table->string('relative_realpath')->comment('文件相对真实路径');
            $table->string('file_type')->index()->comment('文件类型');
            $table->string('mime_type')->index()->comment('文件');
            $table->string('size')->comment('文件大小');
            $table->string('size_desc')->comment('文件大小描述');
            $table->string('ctime')->nullable()->comment('文件创建时间');
            $table->string('mtime')->nullable()->comment('文件修改时间');
            $table->string('atime')->nullable()->comment('文件访问时间');
            $table->string('link_target')->nullable()->comment('文件软连接目标');
            $table->string('url')->nullable()->comment('文件访问链接');
            $table->string('preview_url')->nullable()->comment('文件预览链接');
            $table->string('is_transcoded')->default(false)->index()->comment('文件是否已转码');
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
        Schema::dropIfExists('files');
    }
};
