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
        Schema::create('sso_users', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->nullable()->comment('uid');
            $table->string('username')->comment('用户名');
            $table->string('password')->nullable()->comment('密码');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('avatar')->nullable()->comment('真实姓名');
            $table->string('mobile')->nullable()->comment('手机号');
            $table->string('id_card')->nullable()->comment('身份证');
            $table->dateTime('birthday')->nullable()->comment('生日');
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
        Schema::dropIfExists('sso_users');
    }
};
