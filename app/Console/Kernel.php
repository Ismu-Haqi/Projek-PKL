<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Backup file surat & arsip ke Google Drive — setiap hari jam 01.00
        // Aktifkan dengan set GOOGLE_DRIVE_AUTO_BACKUP=true di .env
        if (config('google-drive.auto_backup_enabled', false)) {
            $schedule->command('backup:drive --type=all --silent')
                     ->dailyAt('01:00')
                     ->withoutOverlapping()
                     ->appendOutputTo(storage_path('logs/backup-drive.log'));
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
