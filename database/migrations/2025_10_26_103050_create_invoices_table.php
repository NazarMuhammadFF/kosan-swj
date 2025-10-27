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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->date('billing_period_start');
            $table->date('billing_period_end');
            $table->unsignedInteger('monthly_rent'); // sewa bulanan
            $table->unsignedInteger('electricity_fee')->default(0); // biaya listrik
            $table->unsignedInteger('water_fee')->default(0); // biaya air
            $table->unsignedInteger('other_fees')->default(0); // biaya lain-lain
            $table->unsignedInteger('late_fee')->default(0); // denda keterlambatan
            $table->unsignedInteger('discount')->default(0); // diskon
            $table->unsignedInteger('total_amount'); // total tagihan
            $table->unsignedInteger('paid_amount')->default(0); // total yang sudah dibayar
            $table->enum('status', ['unpaid', 'partial', 'paid', 'overdue'])->default('unpaid');
            $table->date('due_date');
            $table->date('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('last_reminder_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['contract_id', 'status']);
            $table->index(['tenant_id', 'status']);
            $table->index('invoice_number');
            $table->index('due_date');
            $table->index(['status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
