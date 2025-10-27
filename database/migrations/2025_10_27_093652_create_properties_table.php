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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('address');
            $table->string('city', 100);
            $table->string('province', 100);
            $table->string('postal_code', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('gender_type', ['male', 'female', 'mixed'])->default('mixed');
            $table->json('facilities')->nullable(); // AC, WiFi, Parkir, dll
            $table->json('rules')->nullable(); // Jam malam, tamu, dll
            $table->unsignedInteger('deposit_amount')->default(0);
            $table->json('photos')->nullable(); // array foto URLs
            $table->string('video_url')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['city', 'is_published']);
            $table->index('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
