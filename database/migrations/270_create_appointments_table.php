<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');

            $table->foreignIdFor(\App\Models\Doctor::class)->nullable()->constrained()->nullOnDelete();

            $table->text('message')->nullable();

            $table->timestamp('requested_at')->useCurrent();
            $table->date('appointment_date')->nullable();
            $table->timestamp('read_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
