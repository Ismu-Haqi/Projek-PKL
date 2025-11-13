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
        Schema::table('dispositions', function (Blueprint $table) {
            // Field untuk tracking forwarding
            $table->foreignId('forwarded_from_id')->nullable()->after('to_user_id')->constrained('dispositions')->onDelete('set null');
            $table->foreignId('forwarded_to_id')->nullable()->after('forwarded_from_id')->constrained('dispositions')->onDelete('set null');
            $table->string('forwarding_status')->default('direct')->after('status'); // direct, pending_forward, forwarded
            $table->foreignId('final_recipient_id')->nullable()->after('to_user_id')->constrained('users')->onDelete('cascade');
            $table->text('forwarding_note')->nullable()->after('notes');
            $table->timestamp('forwarded_at')->nullable()->after('completed_at');
            
            // Field untuk bukti penyelesaian
            $table->string('completion_file')->nullable()->after('notes'); // File bukti penyelesaian
            $table->text('completion_description')->nullable()->after('completion_file'); // Deskripsi hasil kerja
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispositions', function (Blueprint $table) {
            $table->dropForeign(['forwarded_from_id']);
            $table->dropForeign(['forwarded_to_id']);
            $table->dropForeign(['final_recipient_id']);
            $table->dropColumn([
                'forwarded_from_id',
                'forwarded_to_id',
                'forwarding_status',
                'final_recipient_id',
                'forwarding_note',
                'forwarded_at'
            ]);
        });
    }
};