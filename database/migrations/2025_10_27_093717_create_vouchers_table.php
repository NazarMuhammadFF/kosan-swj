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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage'); // diskon % atau nominal
            $table->unsignedInteger('value'); // nilai diskon
            $table->unsignedInteger('max_discount')->nullable(); // max diskon untuk type percentage
            $table->unsignedInteger('min_transaction')->nullable(); // min transaksi untuk pakai voucher
            $table->unsignedInteger('usage_limit')->nullable(); // berapa kali bisa dipakai (total)
            $table->unsignedInteger('usage_per_user')->default(1); // berapa kali per user
            $table->unsignedInteger('used_count')->default(0); // sudah dipakai berapa kali
            $table->foreignId('property_id')->nullable()->constrained()->cascadeOnDelete(); // null = all properties
            $table->date('valid_from');
            $table->date('valid_until');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('code');
            $table->index(['is_active', 'valid_from', 'valid_until']);
            $table->index('property_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
