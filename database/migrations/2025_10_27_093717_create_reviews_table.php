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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contract_id')->nullable()->constrained()->nullOnDelete(); // kontrak yang sudah selesai
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->json('photos')->nullable(); // foto dari reviewer
            $table->text('owner_reply')->nullable(); // balasan dari owner
            $table->timestamp('replied_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_verified')->default(false); // review dari tenant terverifikasi
            $table->timestamps();
            $table->softDeletes();

            $table->index(['property_id', 'is_published']);
            $table->index('tenant_id');
            $table->index(['rating', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
