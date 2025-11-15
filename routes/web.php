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
use App\Http\Controllers\DispositionController; 
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AssetBorrowController as AdminAssetBorrowController;
use App\Http\Controllers\Staff\AssetBorrowController as StaffAssetBorrowController;

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
        Route::get('/favorit/list', [ArchiveController::class, 'favorit'])->name('favorit');
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
    
    // ✅ NEW: Peminjaman Aset (Admin)
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
        Route::get('/{id}', [AssetController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AssetController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AssetController::class, 'update'])->name('update');
        Route::delete('/{id}', [AssetController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/status', [AssetController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{id}/qr-download', [AssetController::class, 'downloadQr'])->name('downloadQr');
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
        Route::get('/user', [ReportController::class, 'user'])->name('user');
        Route::get('/periode', [ReportController::class, 'periode'])->name('periode');
        Route::get('/unit-kerja', [ReportController::class, 'unitKerja'])->name('unit-kerja');
        Route::get('/print-pdf', [ReportController::class, 'printPdf'])->name('print-pdf');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
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
        Route::post('/', [ArchiveController::class, 'store'])->name('store');
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
        Route::get('/browse', [StaffAssetBorrowController::class, 'browse'])->name('browse');
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::get('/{id}', [AssetController::class, 'show'])->name('show');
        Route::get('/{id}/qr-download', [AssetController::class, 'downloadQr'])->name('downloadQr');
    });
    
    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/arsip', [ReportController::class, 'arsip'])->name('arsip');
        Route::get('/disposisi', [ReportController::class, 'disposisi'])->name('disposisi');
        Route::get('/periode', [ReportController::class, 'periode'])->name('periode');
        Route::get('/unit-kerja', [ReportController::class, 'unitKerja'])->name('unit-kerja');
        Route::get('/print-pdf', [ReportController::class, 'printPdf'])->name('print-pdf');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
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
        Route::get('/user', [ReportController::class, 'user'])->name('user');
        Route::get('/periode', [ReportController::class, 'periode'])->name('periode');
        Route::get('/unit-kerja', [ReportController::class, 'unitKerja'])->name('unit-kerja');
        Route::get('/print-pdf', [ReportController::class, 'printPdf'])->name('print-pdf');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
    });
    
    // Pengaturan (Limited)
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