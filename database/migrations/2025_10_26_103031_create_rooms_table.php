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
       Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique();   // K1, A-12, dll
            $table->string('floor')->nullable(); // lantai berapa
            $table->unsignedInteger('size_sqm')->nullable(); // ukuran m2
            $table->unsignedInteger('base_price'); // harga dasar per bulan (rupiah)
            $table->boolean('has_ac')->default(false);
            $table->boolean('has_private_bath')->default(false);
            $table->boolean('has_window')->default(true);
            $table->json('facilities')->nullable(); // kasur, lemari, meja, dll
            $table->json('photos')->nullable(); // array foto URLs
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['property_id', 'status']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
