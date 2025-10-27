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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // calon tenant
            $table->enum('type', ['visit', 'direct'])->default('visit'); // kunjungan atau langsung bayar DP
            $table->date('preferred_date')->nullable(); // tanggal kunjungan (untuk type=visit)
            $table->time('preferred_time')->nullable(); // jam kunjungan (untuk type=visit)
            $table->date('move_in_date')->nullable(); // tanggal rencana masuk
            $table->unsignedInteger('dp_amount')->nullable(); // jumlah DP 10%
            $table->string('dp_proof')->nullable(); // bukti bayar DP
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'expired', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users'); // admin yang confirm
            $table->timestamp('expires_at')->nullable(); // auto-expire booking
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['room_id', 'status']);
            $table->index('user_id');
            $table->index('booking_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
