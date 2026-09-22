<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinic_infos', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->json('sub_title');
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('link_text');
            $table->string('link_url')->default('#');
            $table->boolean('is_visible')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_infos');
    }
};
