<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('default.webp');
            $table->string('icon');
            $table->json('title');
            $table->json('description');
            $table->json('content')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->json('meta_description')->nullable();
            $table->boolean('has_detail_page')->default(true);
            $table->boolean('is_visible')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
