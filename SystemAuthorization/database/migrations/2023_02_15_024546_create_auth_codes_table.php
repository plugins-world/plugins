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
        Schema::create('auth_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->comment('客户');
            $table->string('auth_code_type')->comment('授权码类型');
            $table->text('auth_code')->comment('授权码');
            $table->unsignedTinyInteger('is_permanent')->default(0)->comment('是否永久');
            $table->dateTime('start_time')->nullable()->comment('授权开始时间');
            $table->dateTime('end_time')->nullable()->comment('授权截止时间');
            $table->boolean('is_expired')->default(false)->comment('是否到期');
            $table->string('system_domain')->nullable()->comment('系统域名');
            $table->string('ip')->nullable()->comment('客户端 IP');
            $table->dateTime('last_use_time')->nullable()->comment('系统最后一次使用时间');
            $table->unsignedTinyInteger('status')->default(1)->comment('授权码状态: 未使用-1;已使用-2;已过期-3;已撤销-4');
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
        Schema::dropIfExists('auth_codes');
    }
};
