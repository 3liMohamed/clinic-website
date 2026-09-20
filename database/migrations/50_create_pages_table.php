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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('og/default.webp');
            $table->string('name')->unique();
            $table->json('title')->nullable();
            $table->boolean('is_visible');
            $table->json('meta_keywords');
            $table->json('meta_description');
            $table->json('sections');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
