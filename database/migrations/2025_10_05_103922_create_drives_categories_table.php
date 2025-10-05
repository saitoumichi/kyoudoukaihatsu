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
        Schema::create('drives_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('icon', 60)->nullable();
            $table->unsignedInteger('sort')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('name', 'uq_categories_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drives_categories');
    }
};
