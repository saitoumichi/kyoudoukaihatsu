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
        Schema::table('karaokes', function (Blueprint $table) {
            // 既存の不要なカラムを削除
            $table->dropColumn(['room_type', 'has_food_service']);

            // 仕様通りのカラムを追加
            $table->boolean('has_all_you_can_drink')->default(false);
            $table->boolean('byo_allowed')->default(false);
            $table->set('machine_types', ['DAM', 'JOYSOUND'])->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karaokes', function (Blueprint $table) {
            // 追加したカラムを削除
            $table->dropColumn(['has_all_you_can_drink', 'byo_allowed', 'machine_types']);

            // 元のカラムを復元
            $table->string('room_type')->nullable();
            $table->boolean('has_food_service')->default(false);
        });
    }
};
