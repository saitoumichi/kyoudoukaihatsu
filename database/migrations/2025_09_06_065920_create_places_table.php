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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('kana')->nullable();
            $table->string('tel')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->integer('score')->default(0);
            $table->json('tags')->nullable();
            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->integer('rating_count')->default(0);
            $table->integer('recommend_score')->default(0);
            $table->text('reason')->nullable();
            $table->integer('campus_time_min')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('type'); // 'drive', 'karaoke', 'izakaya'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
