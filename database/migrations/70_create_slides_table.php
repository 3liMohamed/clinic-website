<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('default.webp');
            $table->json('title');
            $table->json('description');
            $table->json('primary_button_text');
            $table->string('primary_button_url')->default('#');
            $table->json('secondary_button_text');
            $table->string('secondary_button_url')->default('#');
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
