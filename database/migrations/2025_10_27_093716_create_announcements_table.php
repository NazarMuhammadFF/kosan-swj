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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->nullable()->constrained()->cascadeOnDelete(); // null = untuk semua properti
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->enum('category', ['info', 'warning', 'maintenance', 'promo', 'event', 'other'])->default('info');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->json('attachments')->nullable(); // file attachment URLs
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->date('expires_at')->nullable(); // untuk pengumuman yang expire
            $table->timestamps();
            $table->softDeletes();

            $table->index(['property_id', 'is_published']);
            $table->index('created_by');
            $table->index(['is_published', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
