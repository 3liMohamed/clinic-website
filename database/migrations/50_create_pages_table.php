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
            $table->json('header_title')->nullable();
            $table->json('nav_title')->nullable();
            $table->boolean('is_visible');
            $table->boolean('show_in_main_nav')->default(true);
            $table->boolean('show_in_top_nav')->default(false);
            $table->boolean('is_cta')->default(false);
            $table->json('meta_keywords');
            $table->json('meta_description');
            $table->json('sections');
            $table->unsignedInteger('order')->default(0);
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
