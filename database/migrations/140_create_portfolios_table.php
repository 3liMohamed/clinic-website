<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Category::class)->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('cover_image')->default('default.webp');
            $table->json('gallery')->nullable();
            $table->json('title');
            $table->json('content')->nullable();
            $table->string('client')->nullable();
            $table->date('project_date')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->json('meta_description')->nullable();
            $table->boolean('has_detail_page')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
