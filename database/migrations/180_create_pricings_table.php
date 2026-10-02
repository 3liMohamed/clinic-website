<?php

use App\Enums\PriceSuffix;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->json('title');
            $table->decimal('price', 10, 2);
            $table->unsignedTinyInteger('price_suffix')->default(PriceSuffix::PER_VISIT->value);
            $table->json('button_text');
            $table->string('button_url')->default('#');
            $table->boolean('is_visible')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });

        Schema::create('pricing_features', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Pricing::class)->constrained()->cascadeOnDelete();
            $table->json('text');
            $table->boolean('is_available')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_features');
        Schema::dropIfExists('pricings');
    }
};
