<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Category::class)->nullable()->constrained()->cascadeOnDelete();

            $table->json('question');
            $table->json('answer');

            $table->boolean('is_visible')->default(false);
            $table->unsignedInteger('order')->default(0);

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
