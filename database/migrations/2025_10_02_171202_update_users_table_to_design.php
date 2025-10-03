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
        Schema::table('users', function (Blueprint $table) {
            // 既存のカラムを削除
            $table->dropColumn(['name', 'email', 'password', 'remember_token']);

            // 設計書に合わせてカラムを追加
            $table->string('login_id', 120)->unique()->after('id');
            $table->string('email', 255)->nullable()->unique()->after('login_id');
            $table->string('password_hash', 255)->after('email');
            $table->string('password_algo', 255)->after('password_hash');
            $table->string('role', 255)->after('password_algo');
            $table->tinyInteger('is_active')->default(1)->after('role');
            $table->datetime('last_login_at')->nullable()->after('email_verified_at');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->string('remember_token', 100)->nullable()->after('last_login_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 追加したカラムを削除
            $table->dropColumn([
                'login_id', 'email', 'password_hash', 'password_algo',
                'role', 'is_active', 'last_login_at', 'last_login_ip', 'remember_token'
            ]);

            // 元のカラムを復元
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
        });
    }
};
