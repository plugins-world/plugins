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
        Schema::create('sso_user_sites', function (Blueprint $table) {
            $table->id();
            $table->string('usid')->nullable()->comment('用户站点编号');
            $table->string('site_domain')->comment('站点域名');
            $table->string('uid')->comment('用户编号');
            $table->string('is_login')->default(false)->comment('是否已登录');
            $table->string('token')->comment('登录 token');
            $table->dateTime('expire_time')->nullable()->comment('过期时间');
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
        Schema::dropIfExists('sso_user_sites');
    }
};
