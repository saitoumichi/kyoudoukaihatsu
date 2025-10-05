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
            // 既存のカラムは追加しない
            // 必要なカラムのみ追加（既に存在する場合はスキップ）
            if (!Schema::hasColumn('users', 'login_id')) {
                $table->string('login_id')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'password_hash')) {
                $table->string('password_hash')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'password_algo')) {
                $table->string('password_algo')->default('bcrypt')->after('password_hash');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('password_algo');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip')->nullable()->after('last_login_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'login_id',
                'password_hash',
                'password_algo',
                'role',
                'is_active',
                'last_login_at',
                'last_login_ip'
            ]);
        });
    }
};
