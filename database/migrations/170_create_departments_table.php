<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('default.webp');
            $table->string('icon');
            $table->json('name');
            $table->json('description');
            $table->boolean('is_visible')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });

        Schema::create('department_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Department::class)->constrained()->cascadeOnDelete();
            $table->json('text');
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_items');
        Schema::dropIfExists('departments');
    }
};
