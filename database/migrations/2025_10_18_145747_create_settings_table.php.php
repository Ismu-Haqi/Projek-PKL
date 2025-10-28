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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            // General Settings
            [
                'key' => 'app_name',
                'value' => 'GANDARIA',
                'type' => 'string',
                'description' => 'Nama aplikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'institution_name',
                'value' => 'Dinas Komunikasi dan Informatika Barito Kuala',
                'type' => 'string',
                'description' => 'Nama instansi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_logo',
                'value' => null,
                'type' => 'string',
                'description' => 'Path logo aplikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'string',
                'description' => 'Zona waktu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'language',
                'value' => 'id',
                'type' => 'string',
                'description' => 'Bahasa default',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Security Settings
            [
                'key' => 'session_timeout',
                'value' => '480', // 8 hours in minutes
                'type' => 'integer',
                'description' => 'Timeout sesi login (menit)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'force_https',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Paksa penggunaan HTTPS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Backup Settings
            [
                'key' => 'auto_backup_daily',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Backup otomatis harian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'auto_backup_weekly',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Backup otomatis mingguan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'backup_retention_days',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Lama penyimpanan backup (hari)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'last_backup',
                'value' => null,
                'type' => 'string',
                'description' => 'Waktu backup terakhir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Notification Settings
            [
                'key' => 'email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Aktifkan notifikasi email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'new_archive_notification',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Notifikasi arsip baru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'new_disposition_notification',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Notifikasi disposisi baru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'deadline_reminder',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Pengingat deadline',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'weekly_report',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Laporan mingguan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // SMTP Settings
            [
                'key' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'type' => 'string',
                'description' => 'SMTP host',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'smtp_port',
                'value' => '587',
                'type' => 'integer',
                'description' => 'SMTP port',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'smtp_username',
                'value' => 'noreply@diskominfo.batola.go.id',
                'type' => 'string',
                'description' => 'SMTP username',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'smtp_password',
                'value' => null,
                'type' => 'string',
                'description' => 'SMTP password',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};