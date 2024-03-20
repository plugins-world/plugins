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
        Schema::create('tenant_account_profiles', function (Blueprint $table) {
            $table->comment('租户账户');

            $table->id();
            $table->string('tenant_no')->nullable()->index()->comment('租户编号');
            $table->unsignedBigInteger('account_id')->index()->comment('账户ID');
            $table->unsignedBigInteger('user_id')->index()->comment('用户ID');
            $table->string('mobile')->index()->comment('手机号');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_account_profiles');
    }
};
