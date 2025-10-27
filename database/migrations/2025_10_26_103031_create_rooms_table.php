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
            $table->string('code')->unique();   // K1, A-12, dll
            $table->unsignedInteger('size_sqm')->nullable(); // ukuran m2
            $table->unsignedInteger('base_price'); // harga dasar per bulan (rupiah)
            $table->boolean('has_ac')->default(false);
            $table->boolean('has_private_bath')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
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
