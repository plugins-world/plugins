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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id')->comment('用户 id，users.id');
            $table->unsignedTinyInteger('gender')->after('name')->comment('性别');
            $table->string('avatar')->nullable()->after('gender')->comment('用户头像');
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();

            $table->dropUnique(['email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('gender');
            $table->dropColumn('avatar');
            $table->string('email')->nullable(false)->unique()->change();
            $table->string('password')->change();
        });
    }
};
