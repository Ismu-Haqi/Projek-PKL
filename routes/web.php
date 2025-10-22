<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AssetController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\ReportController; 
use App\Http\Controllers\SettingController; 
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\DispositionController; 
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;

// ✅ Halaman Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ✅ Redirect Root URL
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'staff') {
            return redirect()->route('staff.dashboard');
        }
    }
    
    return redirect()->route('login');
});

// ==================================================
// ✅ ADMIN ROUTES
// ==================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // ✅ AJAX Route untuk Real-time Dashboard Update (OPTIONAL)
    Route::get('dashboard/data', [AdminDashboardController::class, 'getData'])->name('dashboard.data');
    
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
        
        // Route spesifik HARUS SEBELUM route dynamic {id}
        Route::get('/favorit/list', [ArchiveController::class, 'favorit'])->name('favorit');
        
        // Route dengan {id} parameter
        Route::get('/{id}', [ArchiveController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArchiveController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArchiveController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArchiveController::class, 'destroy'])->name('destroy');
        
        // Preview & Download
        Route::get('/{id}/preview', [ArchiveController::class, 'preview'])->name('preview');
        Route::get('/{id}/download', [ArchiveController::class, 'download'])->name('download');
        
        // Favorite
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
        
        // Print & Export
        Route::get('/print-pdf', [ReportController::class, 'printPdf'])->name('print-pdf');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
        
        // DEPRECATED - Backward compatibility
        Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
    });
    
    // ✅ PENGATURAN (SETTINGS) - UPDATED WITH APPEARANCE!
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        // Main page
        Route::get('/', [SettingController::class, 'index'])->name('index');
        
        // Profile settings
        Route::put('/update-profil', [SettingController::class, 'updateProfil'])->name('update-profil');
        
        // System settings
        Route::put('/update', [SettingController::class, 'update'])->name('update');
        
        // Appearance settings ← NEW!
        Route::put('/update-appearance', [SettingController::class, 'updateAppearance'])->name('update-appearance');
        
        // Cache & Maintenance
        Route::post('/clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        
        // Backup
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
// ✅ STAFF ROUTES
// ==================================================
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    
    // Profil
    Route::get('profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::put('profil/password', [ProfileController::class, 'updatePassword'])->name('profil.password');
    
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
        Route::get('/{id}', [DispositionController::class, 'show'])->name('show');
        Route::put('/{id}/status', [DispositionController::class, 'updateStatus'])->name('updateStatus');
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
    
    // Manajemen Aset (Read Only)
    Route::prefix('aset')->name('aset.')->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::get('/{id}', [AssetController::class, 'show'])->name('show');
    });
    
    // Laporan (Read Only)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/arsip', [ReportController::class, 'arsip'])->name('arsip');
        Route::get('/disposisi', [ReportController::class, 'disposisi'])->name('disposisi');
        Route::get('/periode', [ReportController::class, 'periode'])->name('periode');
        
        // Print & Export
        Route::get('/print-pdf', [ReportController::class, 'printPdf'])->name('print-pdf');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
    });
    
    // ✅ PENGATURAN (SETTINGS) - Staff Version (Updated)
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        // Main page
        Route::get('/', [SettingController::class, 'index'])->name('index');
        
        // Profile Only
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    // Profil
    Route::get('profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::put('profil/password', [ProfileController::class, 'updatePassword'])->name('profil.password');
    Route::delete('profil/avatar', [ProfileController::class, 'removeAvatar'])->name('profil.avatar.remove');
});
        
        // Appearance (Staff can also customize appearance) ← NEW!
        Route::put('/update-appearance', [SettingController::class, 'updateAppearance'])->name('update-appearance');
    });
});