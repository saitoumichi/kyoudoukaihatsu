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
        Schema::table('izakayas', function (Blueprint $table) {
            // 既存の不要なカラムを削除
            $table->dropColumn(['cuisine_type', 'has_private_room']);

            // 仕様通りのカラムを追加
            $table->boolean('has_all_you_can_drink')->default(false);
            $table->boolean('byo_allowed')->default(false);
            $table->string('alcohol_types', 120)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('izakayas', function (Blueprint $table) {
            // 追加したカラムを削除
            $table->dropColumn(['has_all_you_can_drink', 'byo_allowed', 'alcohol_types']);

            // 元のカラムを復元
            $table->string('cuisine_type')->nullable();
            $table->boolean('has_private_room')->default(false);
        });
    }
};
