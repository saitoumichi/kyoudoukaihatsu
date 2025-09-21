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
        Schema::create('karaokes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('place_id');
            $table->unsignedInteger('price_min')->nullable();
            $table->unsignedInteger('price_max')->nullable();
            $table->string('room_type', 50)->nullable();
            $table->boolean('has_food_service')->default(false);
            $table->timestamps();

            // 外部キー制約
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karaokes');
    }
};
