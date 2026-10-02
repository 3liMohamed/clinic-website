<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('default.webp');

            $table->json('name');
            $table->json('biography');

            $table->foreignIdFor(\App\Models\Department::class)->constrained()->cascadeOnDelete();

            $table->string('phone')->default('#');
            $table->string('email')->default('#');
            $table->string('address')->default('#');

            $table->json('socials')->nullable();

            $table->json('meta_keywords')->nullable();
            $table->json('meta_description')->nullable();

            $table->boolean('is_home')->default(false);
            $table->boolean('has_detail_page')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });

        Schema::create('doctor_working_hours', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Doctor::class)->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('day')->default(0);
            $table->time('start_time');
            $table->time('end_time');

            $table->boolean('is_visible')->default(false);

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
