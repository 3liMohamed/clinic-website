<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('why_chooses', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('default.webp');
            $table->json('title');
            $table->json('content');
            $table->string('video_url')->default('#');
            $table->boolean('is_visible')->default(false);
            $table->softDeletes();
        });

        Schema::create('why_choose_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\WhyChoose::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->json('text');
            $table->enum('column', ['left', 'right'])->default('left');
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('why_choose_items');
        Schema::dropIfExists('why_chooses');
    }
};
