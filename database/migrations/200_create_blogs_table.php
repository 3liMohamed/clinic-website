<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();

            $table->string('image')->default('default.webp');

            $table->json('title');
            $table->json('description');
            $table->json('content');

            $table->date('published_at')->nullable();

            $table->json('meta_keywords')->nullable();
            $table->json('meta_description')->nullable();

            $table->boolean('is_home')->default(false);
            $table->boolean('has_detail_page')->default(true);
            $table->boolean('is_visible')->default(true);

            $table->unsignedInteger('order')->default(0);

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
