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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('monthly_rent'); // harga sewa per bulan
            $table->unsignedInteger('deposit')->default(0); // deposit
            $table->unsignedInteger('initial_payment')->default(0); // pembayaran pertama
            $table->unsignedTinyInteger('billing_day')->default(1); // tanggal tagihan (1-31)
            $table->json('special_terms')->nullable(); // syarat & ketentuan khusus
            $table->text('rules')->nullable(); // aturan yang disepakati
            $table->string('signed_contract_file')->nullable(); // PDF kontrak yang ditandatangani
            $table->enum('status', ['active', 'ended', 'terminated'])->default('active');
            $table->date('actual_end_date')->nullable(); // tanggal akhir sebenarnya (untuk terminated)
            $table->text('termination_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['room_id', 'status']);
            $table->index(['tenant_id', 'status']);
            $table->index('contract_number');
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
