<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('default.webp');
            $table->json('sub_title');
            $table->json('title');
            $table->json('description');
            $table->boolean('is_visible')->default(false);
            $table->softDeletes();
        });

        Schema::create('about_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\AboutUs::class)->constrained()->cascadeOnDelete();
            $table->string('icon');
            $table->json('text');
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_items');
        Schema::dropIfExists('about_us');
    }
};
