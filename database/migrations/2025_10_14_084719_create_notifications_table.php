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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // disposition, archive, user, system, warning
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Data tambahan (ID, metadata, dll)
            $table->string('icon')->nullable(); // Custom icon jika perlu
            $table->string('url')->nullable(); // URL tujuan ketika notifikasi di-klik
            $table->timestamp('read_at')->nullable(); // Waktu dibaca
            $table->timestamps();
            
            // Index untuk performa
            $table->index('user_id');
            $table->index('read_at');
            $table->index('type');
            $table->index(['user_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};