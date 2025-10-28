<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Disposition;
use App\Models\Archive;
use App\Mail\DispositionCreatedMail;
use App\Mail\DispositionCompletedMail;
use App\Mail\ArchiveUploadedMail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {type=all} {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email notifications (disposition, archive, all)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $email = $this->option('email');
        
        // Jika tidak ada email, gunakan email user pertama
        if (!$email) {
            $user = User::first();
            if (!$user || !$user->email) {
                $this->error('❌ Tidak ada user dengan email di database!');
                $this->info('💡 Gunakan: php artisan email:test all --email=your@email.com');
                return 1;
            }
            $email = $user->email;
        }
        
        $this->info("📧 Testing email ke: {$email}");
        $this->newLine();
        
        try {
            switch ($type) {
                case 'disposition':
                    $this->testDispositionEmail($email);
                    break;
                    
                case 'archive':
                    $this->testArchiveEmail($email);
                    break;
                    
                case 'all':
                    $this->testDispositionEmail($email);
                    $this->testArchiveEmail($email);
                    break;
                    
                default:
                    $this->error('❌ Tipe tidak valid!');
                    $this->info('💡 Gunakan: disposition, archive, atau all');
                    return 1;
            }
            
            $this->newLine();
            $this->info('✅ Test email selesai!');
            $this->info('📬 Cek inbox Anda di: ' . $email);
            $this->info('⏰ Email mungkin butuh beberapa detik untuk sampai');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('🔧 Troubleshooting:');
            $this->line('1. Cek konfigurasi MAIL_ di file .env');
            $this->line('2. Pastikan MAIL_USERNAME dan MAIL_PASSWORD benar');
            $this->line('3. Jika pakai Gmail, pastikan sudah generate App Password');
            $this->line('4. Cek log di storage/logs/laravel.log');
            
            return 1;
        }
    }
    
    /**
     * Test Disposition Email
     */
    private function testDispositionEmail($email)
    {
        $this->info('🔄 Testing Disposition Email...');
        
        // Cari atau buat disposisi dummy
        $disposition = Disposition::with(['fromUser', 'toUser', 'archive'])->first();
        
        if (!$disposition) {
            $this->warn('⚠️  Tidak ada disposisi di database, membuat data dummy...');
            
            // Buat dummy data
            $fromUser = User::where('role', 'admin')->first() ?? User::first();
            $toUser = User::where('role', 'staff')->first() ?? User::skip(1)->first();
            $archive = Archive::first();
            
            if (!$fromUser || !$toUser) {
                $this->error('❌ Tidak cukup user di database untuk test');
                return;
            }
            
            $disposition = new Disposition([
                'nomor_disposisi' => 'TEST/001/2025',
                'from_user_id' => $fromUser->id,
                'to_user_id' => $toUser->id,
                'archive_id' => $archive ? $archive->id : null,
                'subject' => 'Test Disposisi Email',
                'instruction' => 'Ini adalah test email disposisi dari command line',
                'priority' => 'normal',
                'status' => 'pending',
                'deadline' => now()->addDays(7),
            ]);
            
            // Load relasi manual
            $disposition->setRelation('fromUser', $fromUser);
            $disposition->setRelation('toUser', $toUser);
            if ($archive) {
                $disposition->setRelation('archive', $archive);
            }
        }
        
        // Send email
        Mail::to($email)->send(new DispositionCreatedMail($disposition));
        $this->info('✓ Email Disposisi Created terkirim');
        
        // Test Completed Email
        // Set completed_at dulu sebelum create instance Mail
        if (!$disposition->completed_at) {
            $disposition->completed_at = now();
        }
        if (!$disposition->notes) {
            $disposition->notes = 'Test catatan penyelesaian disposisi';
        }
        $disposition->status = 'completed';
        
        Mail::to($email)->send(new DispositionCompletedMail($disposition));
        $this->info('✓ Email Disposisi Completed terkirim');
    }
    
    /**
     * Test Archive Email
     */
    private function testArchiveEmail($email)
    {
        $this->info('🔄 Testing Archive Email...');
        
        // Cari atau buat archive dummy
        $archive = Archive::with(['category', 'uploader'])->first();
        
        if (!$archive) {
            $this->warn('⚠️  Tidak ada arsip di database, membuat data dummy...');
            
            $user = User::first();
            
            if (!$user) {
                $this->error('❌ Tidak ada user di database untuk test');
                return;
            }
            
            $archive = new Archive([
                'nomor_surat' => 'TEST/001/10/2025',
                'judul' => 'Test Arsip Email Notification',
                'tanggal_surat' => now(),
                'tanggal_arsip' => now(),
                'pengirim' => 'Test Pengirim',
                'unit' => 'IKP',
                'jenis_arsip' => 'Surat Masuk',
                'file_path' => 'archives/test.pdf',
                'file_name' => 'test.pdf',
                'user_id' => $user->id,
            ]);
            
            // Load relasi manual
            $archive->setRelation('uploader', $user);
        }
        
        // Send email
        $recipientName = explode(' ', $email)[0]; // Ambil nama dari email
        Mail::to($email)->send(new ArchiveUploadedMail($archive, $recipientName));
        $this->info('✓ Email Archive Uploaded terkirim');
    }
}