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
        if (! Schema::hasTable('accounts')) {
            Schema::create('accounts', function (Blueprint $table) {
                $table->comment('账号主表');

                $table->bigIncrements('id');
                $table->string('aid', 32)->unique('aid');
                $table->unsignedTinyInteger('type')->default(3);
                $table->string('country_code', 8)->nullable();
                $table->string('pure_phone', 128)->nullable();
                $table->string('phone', 128)->nullable()->unique('phone');
                $table->string('email', 128)->nullable()->unique('email');
                $table->string('password', 64)->nullable();
                $table->timestamp('last_login_at');
                $table->unsignedTinyInteger('is_verify')->default(0);
                $table->string('verify_plugin_fskey', 32)->nullable();
                $table->string('verify_real_name', 128)->nullable();
                $table->unsignedTinyInteger('verify_gender')->default(1);
                $table->string('verify_cert_type', 32)->nullable();
                $table->string('verify_cert_number', 128)->nullable();
                $table->unsignedTinyInteger('verify_identity_type')->nullable();
                $table->timestamp('verify_at')->nullable();
                $table->text('verify_log')->nullable();
                $table->unsignedTinyInteger('is_enabled')->default(1);
                $table->unsignedTinyInteger('wait_delete')->default(0);
                $table->timestamp('wait_delete_at')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->nullable();
                $table->softDeletes();
            });
        }
        if (! Schema::hasTable('account_users')) {
            Schema::create('account_users', function (Blueprint $table) {
                $table->comment('账号关联用户表');

                $table->id();
                $table->unsignedBigInteger('account_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('account_connects')) {
            Schema::create('account_connects', function (Blueprint $table) {
                $table->comment('账号互联凭证表');

                $table->bigIncrements('id');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->unsignedTinyInteger('connect_platform_id');
                $table->string('connect_account_id', 128);
                $table->string('connect_token', 128)->nullable();
                $table->string('connect_refresh_token', 128)->nullable();
                $table->string('connect_username', 128)->nullable();
                $table->string('connect_nickname', 128)->nullable();
                $table->string('connect_avatar')->nullable();
                $table->string('plugin_fskey', 64);
                $table->unsignedTinyInteger('is_enabled')->default(1);
                switch (config('database.default')) {
                    case 'pgsql':
                        $table->jsonb('more_json')->nullable();
                        break;

                    case 'sqlsrv':
                        $table->nvarchar('more_json', 'max')->nullable();
                        break;

                    default:
                        $table->json('more_json')->nullable();
                }
                $table->timestamp('refresh_token_expired_at')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->nullable();
                $table->softDeletes();

                $table->unique(['connect_platform_id', 'connect_account_id'], 'connect_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('accounts')) {
            Schema::dropIfExists('accounts');
        }
        if (Schema::hasTable('account_users')) {
            Schema::dropIfExists('account_users');
        }
        if (Schema::hasTable('account_connects')) {
            Schema::dropIfExists('account_connects');
        }
    }
};
