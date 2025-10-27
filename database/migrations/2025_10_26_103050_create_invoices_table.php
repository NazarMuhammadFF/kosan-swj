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
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->date('billing_period_start');
            $table->date('billing_period_end');
            $table->unsignedInteger('amount'); // total tagihan
            $table->unsignedInteger('paid_amount')->default(0);
            $table->enum('status', ['unpaid','partial','paid','overdue'])->default('unpaid');
            $table->date('due_date');
            $table->timestamps();
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
