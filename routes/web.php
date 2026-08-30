<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Pimpinan\DashboardController as PimpinanDashboardController; 
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AssetController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\ReportController; 
use App\Http\Controllers\SettingController; 
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\RetentionScheduleController;
use App\Http\Controllers\DispositionController; 
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AssetBorrowController as AdminAssetBorrowController;
use App\Http\Controllers\Staff\AssetBorrowController as StaffAssetBorrowController;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\GoogleDriveBackupController;
use App\Http\Controllers\AssetMutationController;
use App\Http\Controllers\AssetDestructionController;
use App\Http\Controllers\DashboardGalleryController;
use App\Http\Controllers\AssetLocationController;
use App\Http\Controllers\OutgoingLetterController;
use App\Http\Controllers\LaporanPengajuanController;
use App\Models\DocumentSignature;

// ── Route publik: validasi TTE (tidak perlu login) ──────────────────────────
Route::get('/validasi/{token}', function (string $token) {
    $signature = DocumentSignature::where('token', $token)->first();
    return view('validasi', compact('signature'));
})->name('validasi.tte');

// Halaman Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Redirect Root URL
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'staff') {
            return redirect()->route('staff.dashboard');
        } elseif ($role === 'pimpinan') {
            return redirect()->route('pimpinan.dashboard');
        }
    }
    
    return redirect()->route('login');
});

// ==================================================
// ADMIN ROUTES
// ==================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/data', [AdminDashboardController::class, 'getData'])->name('dashboard.data');
    Route::get('dashboard/chart-data', [AdminDashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    
    // Profil
    Route::get('profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::put('profil/password', [ProfileController::class, 'updatePassword'])->name('profil.password');
    Route::delete('profil/avatar', [ProfileController::class, 'removeAvatar'])->name('profil.avatar.remove');
    
    // Arsip Digital
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/', [ArchiveController::class, 'index'])->name('index');
        Route::get('/create', [ArchiveController::class, 'create'])->name('create');
        Route::post('/store', [ArchiveController::class, 'store'])->name('store');
        Route::get('/favorit', [ArchiveController::class, 'favorit'])->name('favorit');
        Route::get('/{id}', [ArchiveController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArchiveController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArchiveController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArchiveController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/preview', [ArchiveController::class, 'preview'])->name('preview');
        Route::get('/{id}/download', [ArchiveController::class, 'download'])->name('download');
        Route::post('/{id}/favorite', [ArchiveController::class, 'toggleFavorite'])->name('favorite');
    });
    
    // Disposisi
    Route::prefix('disposisi')->name('disposisi.')->group(function () {
        Route::get('/', [DispositionController::class, 'index'])->name('index');
        Route::get('/create', [DispositionController::class, 'create'])->name('create');
        Route::post('/', [DispositionController::class, 'store'])->name('store');
        Route::get('/{id}', [DispositionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DispositionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DispositionController::class, 'update'])->name('update');
        Route::delete('/{id}', [DispositionController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/status', [DispositionController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/api/items', [DispositionController::class, 'getItems'])->name('api.items');
        Route::post('/{id}/forward', [DispositionController::class, 'forwardDisposition'])->name('forward');
        Route::get('/needs-forwarding', [DispositionController::class, 'needsForwarding'])->name('needsForwarding');
        Route::get('/{id}/download-completion', [DispositionController::class, 'downloadCompletionFile'])->name('downloadCompletion');
    });
    
    // Peminjaman Aset (Admin)
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [AdminAssetBorrowController::class, 'index'])->name('index');
        Route::get('/menunggu', [AdminAssetBorrowController::class, 'pending'])->name('pending');
        Route::get('/jatuh-tempo', [AdminAssetBorrowController::class, 'duesoon'])->name('duesoon');
        Route::get('/terlambat', [AdminAssetBorrowController::class, 'overdue'])->name('overdue');
        Route::get('/{id}', [AdminAssetBorrowController::class, 'show'])->name('show');
        Route::post('/{id}/setujui', [AdminAssetBorrowController::class, 'approve'])->name('approve');
        Route::post('/{id}/tolak', [AdminAssetBorrowController::class, 'reject'])->name('reject');
        Route::post('/{id}/serahkan', [AdminAssetBorrowController::class, 'handover'])->name('handover');
        Route::post('/{id}/terima-kembali', [AdminAssetBorrowController::class, 'returnAsset'])->name('return');
        Route::delete('/{id}', [AdminAssetBorrowController::class, 'destroy'])->name('destroy');
    });

    // Mutasi Aset
    Route::prefix('mutasi')->name('mutasi.')->group(function () {
        Route::get('/', [AssetMutationController::class, 'index'])->name('index');
        Route::get('/create', [AssetMutationController::class, 'create'])->name('create');
        Route::post('/', [AssetMutationController::class, 'store'])->name('store');
        Route::get('/{id}', [AssetMutationController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [AssetMutationController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [AssetMutationController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [AssetMutationController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download-ba', [AssetMutationController::class, 'downloadBeritaAcara'])->name('download-ba');
    });

    // Pemusnahan Aset (Admin - approve/reject)
    Route::prefix('pemusnahan')->name('pemusnahan.')->group(function () {
        Route::get('/', [AssetDestructionController::class, 'index'])->name('index');
        Route::get('/{id}', [AssetDestructionController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [AssetDestructionController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [AssetDestructionController::class, 'reject'])->name('reject');
        Route::get('/{id}/download-ba', [AssetDestructionController::class, 'downloadBeritaAcara'])->name('download-ba');
    });
    // Galeri Dashboard (Admin - kelola gambar dokumentasi)
    Route::prefix('galeri')->name('galeri.')->group(function () {
        Route::get('/', [DashboardGalleryController::class, 'index'])->name('index');
        Route::post('/', [DashboardGalleryController::class, 'store'])->name('store');
        Route::put('/{id}', [DashboardGalleryController::class, 'update'])->name('update');
        Route::delete('/{id}', [DashboardGalleryController::class, 'destroy'])->name('destroy');
    });

    // Denah Lokasi Fisik Aset
    Route::prefix('denah-aset')->name('denah-aset.')->group(function () {
        Route::get('/', [AssetLocationController::class, 'index'])->name('index');
        Route::get('/kelola', [AssetLocationController::class, 'kelola'])->name('kelola');
        Route::post('/{id}/posisi', [AssetLocationController::class, 'simpanPosisi'])->name('simpan-posisi');
        Route::delete('/{id}/posisi', [AssetLocationController::class, 'hapusPosisi'])->name('hapus-posisi');
    });


    // Notifikasi
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read.post');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');
    });
    
    // Manajemen Aset
    Route::prefix('aset')->name('aset.')->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::get('/create', [AssetController::class, 'create'])->name('create');
        Route::post('/', [AssetController::class, 'store'])->name('store');
        Route::get('/scan', [AssetController::class, 'scanPage'])->name('scan');
        Route::post('/scan/lookup', [AssetController::class, 'scanLookup'])->name('scan.lookup');
        Route::post('/scan/save', [AssetController::class, 'scanSave'])->name('scan.save');
        Route::get('/{id}', [AssetController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AssetController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AssetController::class, 'update'])->name('update');
        Route::delete('/{id}', [AssetController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/status', [AssetController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{id}/qr-download', [AssetController::class, 'downloadQr'])->name('downloadQr');
    });

    // Jadwal Retensi Arsip (JRA) - Poin 2 revisi, Admin kelola penuh
    Route::prefix('retensi')->name('retensi.')->group(function () {
        Route::get('/', [RetentionScheduleController::class, 'index'])->name('index');
        Route::get('/create', [RetentionScheduleController::class, 'create'])->name('create');
        Route::post('/', [RetentionScheduleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RetentionScheduleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RetentionScheduleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RetentionScheduleController::class, 'destroy'])->name('destroy');
    });
    
    // Manajemen User
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::patch('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggleStatus');
    });
    
    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/arsip', [ReportController::class, 'arsip'])->name('arsip');
        Route::get('/disposisi', [ReportController::class, 'disposisi'])->name('disposisi');
        Route::get('/aset', [ReportController::class, 'aset'])->name('aset');
        Route::get('/user', [ReportController::class, 'user'])->name('user');
        Route::get('/surat-masuk', [ReportController::class, 'suratMasuk'])->name('surat-masuk');
        Route::get('/periode', [ReportController::class, 'periode'])->name('periode');
        Route::get('/unit-kerja', [ReportController::class, 'unitKerja'])->name('unit-kerja');
        Route::get('/print-pdf', [ReportController::class, 'printPdf'])->name('print-pdf');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
        Route::get('/penyusutan', [ReportController::class, 'penyusutan'])->name('penyusutan');
        Route::get('/peminjaman', [ReportController::class, 'peminjaman'])->name('peminjaman');
        Route::get('/maintenance', [ReportController::class, 'maintenance'])->name('maintenance');
        Route::get('/pemusnahan', [ReportController::class, 'pemusnahan'])->name('pemusnahan');
        Route::get('/agenda-surat', [ReportController::class, 'agendaSurat'])->name('agenda-surat');
        Route::get('/surat-keluar', [ReportController::class, 'suratKeluar'])->name('surat-keluar');
        Route::get('/beban-kerja-pimpinan', [ReportController::class, 'bebanKerjaPimpinan'])->name('beban-kerja-pimpinan');

        // Pengajuan TTE ke pimpinan
        Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
            Route::get('/', [LaporanPengajuanController::class, 'index'])->name('index');
            Route::post('/ajukan', [LaporanPengajuanController::class, 'ajukan'])->name('ajukan');
            Route::post('/{id}/ajukan-ulang', [LaporanPengajuanController::class, 'ajukanUlang'])->name('ajukan-ulang');
            Route::get('/{id}/download', [LaporanPengajuanController::class, 'download'])->name('download');
        });
    });
    
    // Pengaturan
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/update-profil', [SettingController::class, 'updateProfil'])->name('update-profil');
        Route::put('/update', [SettingController::class, 'update'])->name('update');
        Route::put('/update-appearance', [SettingController::class, 'updateAppearance'])->name('update-appearance');
        Route::post('/clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        Route::post('/backup', [SettingController::class, 'backup'])->name('backup');
        Route::get('/backup-list', [SettingController::class, 'backupList'])->name('backup-list');
        Route::get('/backup/download/{filename}', [SettingController::class, 'downloadBackup'])->name('backup-download');
        Route::delete('/backup/{filename}', [SettingController::class, 'deleteBackup'])->name('backup-delete');

        // Google Drive Backup
        Route::prefix('gdrive')->name('gdrive.')->group(function () {
            Route::get('/status', [GoogleDriveBackupController::class, 'status'])->name('status');
            Route::post('/test', [GoogleDriveBackupController::class, 'testConnection'])->name('test');
            Route::post('/backup-arsip', [GoogleDriveBackupController::class, 'backupArsip'])->name('backup-arsip');
            Route::post('/backup-disposisi', [GoogleDriveBackupController::class, 'backupDisposisi'])->name('backup-disposisi');
            Route::post('/backup-all', [GoogleDriveBackupController::class, 'backupAll'])->name('backup-all');
        });
    });
    
    // Surat Masuk (Admin)
    Route::prefix('surat-masuk')->name('surat-masuk.')->group(function () {
        Route::get('/',                    [IncomingLetterController::class, 'index'])->name('index');
        Route::get('/create',              [IncomingLetterController::class, 'create'])->name('create');
        Route::post('/',                   [IncomingLetterController::class, 'store'])->name('store');
        Route::get('/{id}',                [IncomingLetterController::class, 'show'])->name('show');
        Route::get('/{id}/edit',           [IncomingLetterController::class, 'edit'])->name('edit');
        Route::put('/{id}',                [IncomingLetterController::class, 'update'])->name('update');
        Route::delete('/{id}',             [IncomingLetterController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/preview',        [IncomingLetterController::class, 'preview'])->name('preview');
        Route::get('/{id}/download',       [IncomingLetterController::class, 'download'])->name('download');
        Route::get('/{id}/buat-disposisi', [IncomingLetterController::class, 'buatDisposisi'])->name('buat-disposisi');
        Route::post('/{id}/tandai-selesai',[IncomingLetterController::class, 'tandaiSelesai'])->name('tandai-selesai');
    });

    // Surat Keluar
    Route::prefix('surat-keluar')->name('surat-keluar.')->group(function () {
        Route::get('/', [OutgoingLetterController::class, 'index'])->name('index');
        Route::get('/create', [OutgoingLetterController::class, 'create'])->name('create');
        Route::post('/', [OutgoingLetterController::class, 'store'])->name('store');
        Route::get('/{id}', [OutgoingLetterController::class, 'show'])->name('show');
        Route::get('/{id}/download', [OutgoingLetterController::class, 'download'])->name('download');
        Route::get('/{id}/download-pdf', [OutgoingLetterController::class, 'downloadPdf'])->name('download-pdf');
        Route::post('/{id}/ajukan-tte', [OutgoingLetterController::class, 'ajukanTte'])->name('ajukan-tte');
        Route::delete('/{id}', [OutgoingLetterController::class, 'destroy'])->name('destroy');
    });

    // Kategori
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });
});

// ==================================================
// STAFF ROUTES
// ==================================================
Route::get('/aset/view/{id}', [AssetController::class, 'publicShow'])->name('aset.public.show');

Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    
    // Profil
    Route::get('profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::put('profil/password', [ProfileController::class, 'updatePassword'])->name('profil.password');
    Route::delete('profil/avatar', [ProfileController::class, 'removeAvatar'])->name('profil.avatar.remove');
    
    // Arsip Digital
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/', [ArchiveController::class, 'index'])->name('index');
        Route::get('/create', [ArchiveController::class, 'create'])->name('create'); 
        Route::post('/store', [ArchiveController::class, 'store'])->name('store'); 
        Route::get('/favorit', [ArchiveController::class, 'favorit'])->name('favorit');
        Route::get('/{id}', [ArchiveController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArchiveController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArchiveController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArchiveController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/favorite', [ArchiveController::class, 'toggleFavorite'])->name('favorite');
        Route::get('/download/{id}', [ArchiveController::class, 'download'])->name('download');
        Route::get('/{id}/preview', [ArchiveController::class, 'preview'])->name('preview');
    });
    
    // Disposisi
Route::prefix('disposisi')->name('disposisi.')->group(function () {
    Route::get('/', [DispositionController::class, 'index'])->name('index');
    Route::get('/create', [DispositionController::class, 'create'])->name('create');
    Route::post('/', [DispositionController::class, 'store'])->name('store');
    Route::get('/{id}', [DispositionController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [DispositionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [DispositionController::class, 'update'])->name('update');
    Route::delete('/{id}', [DispositionController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/status', [DispositionController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/{id}/download-completion', [DispositionController::class, 'downloadCompletionFile'])->name('downloadCompletion');
});
    
    // Peminjaman Aset (Staff)
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/browse', [StaffAssetBorrowController::class, 'browse'])->name('browse');
        Route::get('/', [StaffAssetBorrowController::class, 'index'])->name('index');
        Route::get('/create', [StaffAssetBorrowController::class, 'create'])->name('create');
        Route::post('/store', [StaffAssetBorrowController::class, 'store'])->name('store');
        Route::get('/{id}', [StaffAssetBorrowController::class, 'show'])->name('show');
        Route::delete('/{id}', [StaffAssetBorrowController::class, 'destroy'])->name('destroy');
    });

    // Mutasi Aset (Staff)
    Route::prefix('mutasi')->name('mutasi.')->group(function () {
        Route::get('/', [AssetMutationController::class, 'index'])->name('index');
        Route::get('/create', [AssetMutationController::class, 'create'])->name('create');
        Route::post('/', [AssetMutationController::class, 'store'])->name('store');
        Route::get('/{id}', [AssetMutationController::class, 'show'])->name('show');
        Route::delete('/{id}', [AssetMutationController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download-ba', [AssetMutationController::class, 'downloadBeritaAcara'])->name('download-ba');
    });

    // Pemusnahan Aset (Staff - ajukan usulan)
    Route::prefix('pemusnahan')->name('pemusnahan.')->group(function () {
        Route::get('/', [AssetDestructionController::class, 'index'])->name('index');
        Route::get('/create', [AssetDestructionController::class, 'create'])->name('create');
        Route::post('/', [AssetDestructionController::class, 'store'])->name('store');
        Route::get('/{id}', [AssetDestructionController::class, 'show'])->name('show');
        Route::delete('/{id}', [AssetDestructionController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download-ba', [AssetDestructionController::class, 'downloadBeritaAcara'])->name('download-ba');
    });

    // Denah Lokasi Fisik Aset (view saja)
    Route::prefix('denah-aset')->name('denah-aset.')->group(function () {
        Route::get('/', [AssetLocationController::class, 'index'])->name('index');
    });

    // Notifikasi
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read.post');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');
    });
    
    // Manajemen Aset (Read-Only + Upload unit sendiri)
    Route::prefix('aset')->name('aset.')->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::get('/browse', [StaffAssetBorrowController::class, 'browse'])->name('browse');
        Route::get('/create', [AssetController::class, 'create'])->name('create');
        Route::post('/', [AssetController::class, 'store'])->name('store');
        Route::get('/scan', [AssetController::class, 'scanPage'])->name('scan');
        Route::post('/scan/lookup', [AssetController::class, 'scanLookup'])->name('scan.lookup');
        Route::post('/scan/save', [AssetController::class, 'scanSave'])->name('scan.save');
        Route::get('/{id}/edit', [AssetController::class, 'edit'])->name('edit');
        Route::get('/{id}/qr-download', [AssetController::class, 'downloadQr'])->name('downloadQr');
        Route::get('/{id}', [AssetController::class, 'show'])->name('show');
        Route::put('/{id}', [AssetController::class, 'update'])->name('update');
        Route::delete('/{id}', [AssetController::class, 'destroy'])->name('destroy');
    });

    // Mutasi Aset (Pimpinan - read only)
    Route::prefix('mutasi')->name('mutasi.')->group(function () {
        Route::get('/', [AssetMutationController::class, 'index'])->name('index');
        Route::get('/create', [AssetMutationController::class, 'create'])->name('create');
        Route::post('/', [AssetMutationController::class, 'store'])->name('store');
        Route::get('/{id}', [AssetMutationController::class, 'show'])->name('show');
        Route::get('/{id}/download-ba', [AssetMutationController::class, 'downloadBeritaAcara'])->name('download-ba');
    });

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/arsip', [ReportController::class, 'arsip'])->name('arsip');
        Route::get('/disposisi', [ReportController::class, 'disposisi'])->name('disposisi');
        Route::get('/surat-masuk', [ReportController::class, 'suratMasuk'])->name('surat-masuk');
        Route::get('/peminjaman', [ReportController::class, 'peminjaman'])->name('peminjaman');
        Route::get('/periode', [ReportController::class, 'periode'])->name('periode');
        Route::get('/unit-kerja', [ReportController::class, 'unitKerja'])->name('unit-kerja');
        Route::get('/print-pdf', [ReportController::class, 'printPdf'])->name('print-pdf');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
        Route::get('/pemusnahan', [ReportController::class, 'pemusnahan'])->name('pemusnahan');
        Route::get('/agenda-surat', [ReportController::class, 'agendaSurat'])->name('agenda-surat');
        Route::get('/surat-keluar', [ReportController::class, 'suratKeluar'])->name('surat-keluar');

        // Pengajuan TTE ke pimpinan
        Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
            Route::get('/', [LaporanPengajuanController::class, 'index'])->name('index');
            Route::post('/ajukan', [LaporanPengajuanController::class, 'ajukan'])->name('ajukan');
            Route::post('/{id}/ajukan-ulang', [LaporanPengajuanController::class, 'ajukanUlang'])->name('ajukan-ulang');
            Route::get('/{id}/download', [LaporanPengajuanController::class, 'download'])->name('download');
        });
    });
    
    // Surat Masuk (Staff)
    Route::prefix('surat-masuk')->name('surat-masuk.')->group(function () {
        Route::get('/',                    [IncomingLetterController::class, 'index'])->name('index');
        Route::get('/create',              [IncomingLetterController::class, 'create'])->name('create');
        Route::post('/',                   [IncomingLetterController::class, 'store'])->name('store');
        Route::get('/{id}',                [IncomingLetterController::class, 'show'])->name('show');
        Route::get('/{id}/edit',           [IncomingLetterController::class, 'edit'])->name('edit');
        Route::put('/{id}',                [IncomingLetterController::class, 'update'])->name('update');
        Route::delete('/{id}',             [IncomingLetterController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/preview',        [IncomingLetterController::class, 'preview'])->name('preview');
        Route::get('/{id}/download',       [IncomingLetterController::class, 'download'])->name('download');
        Route::get('/{id}/buat-disposisi', [IncomingLetterController::class, 'buatDisposisi'])->name('buat-disposisi');
        Route::post('/{id}/tandai-selesai',[IncomingLetterController::class, 'tandaiSelesai'])->name('tandai-selesai');
    });

    // Surat Keluar
    Route::prefix('surat-keluar')->name('surat-keluar.')->group(function () {
        Route::get('/', [OutgoingLetterController::class, 'index'])->name('index');
        Route::get('/create', [OutgoingLetterController::class, 'create'])->name('create');
        Route::post('/', [OutgoingLetterController::class, 'store'])->name('store');
        Route::get('/{id}', [OutgoingLetterController::class, 'show'])->name('show');
        Route::get('/{id}/download', [OutgoingLetterController::class, 'download'])->name('download');
        Route::get('/{id}/download-pdf', [OutgoingLetterController::class, 'downloadPdf'])->name('download-pdf');
        Route::post('/{id}/ajukan-tte', [OutgoingLetterController::class, 'ajukanTte'])->name('ajukan-tte');
        Route::delete('/{id}', [OutgoingLetterController::class, 'destroy'])->name('destroy');
    });

    // Pengaturan
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/update-profil', [SettingController::class, 'updateProfil'])->name('update-profil');
        Route::put('/update-appearance', [SettingController::class, 'updateAppearance'])->name('update-appearance');
    });
});

// ==================================================
// PIMPINAN ROUTES
// ==================================================
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [PimpinanDashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/data', [PimpinanDashboardController::class, 'getData'])->name('dashboard.data');
    Route::get('dashboard/chart-data', [PimpinanDashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    
    // Profil
    Route::get('profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::put('profil/password', [ProfileController::class, 'updatePassword'])->name('profil.password');
    Route::delete('profil/avatar', [ProfileController::class, 'removeAvatar'])->name('profil.avatar.remove');
    
    // Surat Keluar (lihat & setujui/tolak TTE)
    Route::prefix('surat-keluar')->name('surat-keluar.')->group(function () {
        Route::get('/', [OutgoingLetterController::class, 'index'])->name('index');
        Route::get('/{id}', [OutgoingLetterController::class, 'show'])->name('show');
        Route::get('/{id}/download', [OutgoingLetterController::class, 'download'])->name('download');
        Route::get('/{id}/download-pdf', [OutgoingLetterController::class, 'downloadPdf'])->name('download-pdf');
        Route::post('/{id}/setujui-tte', [OutgoingLetterController::class, 'setujuiTte'])->name('setujui-tte');
        Route::post('/{id}/tolak-tte', [OutgoingLetterController::class, 'tolakTte'])->name('tolak-tte');
    });
    
    // Arsip Digital (Read-Only + Favorit)
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/', [ArchiveController::class, 'index'])->name('index');
        Route::get('/create', [ArchiveController::class, 'create'])->name('create'); 
        Route::post('/', [ArchiveController::class, 'store'])->name('store'); 
        Route::get('/favorit', [ArchiveController::class, 'favorit'])->name('favorit');
        Route::get('/{id}', [ArchiveController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArchiveController::class, 'edit'])->name('edit'); 
        Route::put('/{id}', [ArchiveController::class, 'update'])->name('update'); 
        Route::delete('/{id}', [ArchiveController::class, 'destroy'])->name('destroy'); 
        Route::get('/{id}/preview', [ArchiveController::class, 'preview'])->name('preview');
        Route::get('/{id}/download', [ArchiveController::class, 'download'])->name('download');
        Route::post('/{id}/favorite', [ArchiveController::class, 'toggleFavorite'])->name('favorite');
    });
    
    // Disposisi
    Route::prefix('disposisi')->name('disposisi.')->group(function () {
        Route::get('/', [DispositionController::class, 'index'])->name('index');
        Route::get('/create', [DispositionController::class, 'create'])->name('create');
        Route::post('/', [DispositionController::class, 'store'])->name('store');
        Route::get('/{id}', [DispositionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DispositionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DispositionController::class, 'update'])->name('update');
        Route::delete('/{id}', [DispositionController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/status', [DispositionController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{id}/download-completion', [DispositionController::class, 'downloadCompletionFile'])->name('downloadCompletion');
    });
    
    // Notifikasi
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read.post');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');
    });
    
    // Manajemen Aset (Read-Only)
    Route::prefix('aset')->name('aset.')->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::get('/{id}', [AssetController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AssetController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AssetController::class, 'update'])->name('update');
        Route::delete('/{id}', [AssetController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/status', [AssetController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{id}/qr-download', [AssetController::class, 'downloadQr'])->name('downloadQr');
    });

    // Jadwal Retensi Arsip (JRA) - Read-Only
    Route::get('/retensi', [RetentionScheduleController::class, 'index'])->name('retensi.index');
    
    Route::prefix('mutasi')->name('mutasi.')->group(function () {
        Route::get('/', [AssetMutationController::class, 'index'])->name('index');
        Route::get('/create', [AssetMutationController::class, 'create'])->name('create');
        Route::post('/', [AssetMutationController::class, 'store'])->name('store');
        Route::get('/{id}', [AssetMutationController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [AssetMutationController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [AssetMutationController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [AssetMutationController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download-ba', [AssetMutationController::class, 'downloadBeritaAcara'])->name('download-ba');
    });

    // Pemusnahan Aset (Pimpinan - akses unduh Berita Acara dari Laporan)
    Route::prefix('pemusnahan')->name('pemusnahan.')->group(function () {
        Route::get('/{id}/download-ba', [AssetDestructionController::class, 'downloadBeritaAcara'])->name('download-ba');
    });

    // Denah Lokasi Fisik Aset (view saja)
    Route::prefix('denah-aset')->name('denah-aset.')->group(function () {
        Route::get('/', [AssetLocationController::class, 'index'])->name('index');
    });

    // Manajemen User (Read-Only)
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
    });
    
    // Laporan (Full Access)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/arsip', [ReportController::class, 'arsip'])->name('arsip');
        Route::get('/disposisi', [ReportController::class, 'disposisi'])->name('disposisi');
        Route::get('/aset', [ReportController::class, 'aset'])->name('aset');
        Route::get('/user', [ReportController::class, 'user'])->name('user');
        Route::get('/surat-masuk', [ReportController::class, 'suratMasuk'])->name('surat-masuk');
        Route::get('/periode', [ReportController::class, 'periode'])->name('periode');
        Route::get('/unit-kerja', [ReportController::class, 'unitKerja'])->name('unit-kerja');
        Route::get('/print-pdf', [ReportController::class, 'printPdf'])->name('print-pdf');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
        Route::get('/penyusutan', [ReportController::class, 'penyusutan'])->name('penyusutan');
        Route::get('/peminjaman', [ReportController::class, 'peminjaman'])->name('peminjaman');
        Route::get('/maintenance', [ReportController::class, 'maintenance'])->name('maintenance');
        Route::get('/pemusnahan', [ReportController::class, 'pemusnahan'])->name('pemusnahan');
        Route::get('/agenda-surat', [ReportController::class, 'agendaSurat'])->name('agenda-surat');
        Route::get('/surat-keluar', [ReportController::class, 'suratKeluar'])->name('surat-keluar');
        Route::get('/beban-kerja-pimpinan', [ReportController::class, 'bebanKerjaPimpinan'])->name('beban-kerja-pimpinan');

        // Validasi TTE pengajuan laporan
        Route::prefix('validasi')->name('validasi.')->group(function () {
            Route::get('/', [LaporanPengajuanController::class, 'daftarValidasi'])->name('index');
            Route::get('/{id}/preview', [LaporanPengajuanController::class, 'previewValidasi'])->name('preview');
            Route::post('/{id}/setujui', [LaporanPengajuanController::class, 'setujui'])->name('setujui');
            Route::post('/{id}/tolak', [LaporanPengajuanController::class, 'tolak'])->name('tolak');
            Route::get('/{id}/download', [LaporanPengajuanController::class, 'downloadPimpinan'])->name('download');
        });
    });
    
    // Surat Masuk (Pimpinan - read only)
    Route::prefix('surat-masuk')->name('surat-masuk.')->group(function () {
        Route::get('/',              [IncomingLetterController::class, 'index'])->name('index');
        Route::get('/{id}',          [IncomingLetterController::class, 'show'])->name('show');
        Route::get('/{id}/preview',  [IncomingLetterController::class, 'preview'])->name('preview');
        Route::get('/{id}/download', [IncomingLetterController::class, 'download'])->name('download');
    });

    // Pengaturan (Profile & Password Only - Sama seperti Staff)
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/update-profil', [SettingController::class, 'updateProfil'])->name('update-profil');
    });

    // Profil - Avatar Management
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::delete('/avatar', [SettingController::class, 'removeAvatar'])->name('avatar.remove');
    });
});