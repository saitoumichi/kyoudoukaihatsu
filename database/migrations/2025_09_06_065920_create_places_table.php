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
            $table->string('name', 120);
            $table->string('kana', 120)->nullable();
            $table->string('tel', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('url', 255)->nullable();
            $table->integer('score')->default(0);
            $table->string('tags', 255)->nullable();
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->integer('recommend_score')->default(0);
            $table->string('reason', 255)->nullable();
            $table->unsignedSmallInteger('campus_time_min')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('type', ['drive', 'karaoke', 'izakaya']);
            $table->timestamps();

            // インデックス
            $table->index('name');
            $table->index('kana');
            $table->index('type');
            $table->index('is_active');
            $table->index('score');
            $table->index('rating_avg');
            $table->index('campus_time_min');
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
