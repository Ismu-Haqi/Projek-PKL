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


// Halaman Login dan Logout
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ====================================================================
// RUTE ADMIN (FULL ACCESS)
// ====================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // ARSIP (ArchiveController)
    Route::get('arsip-digital', [ArchiveController::class, 'index'])->name('arsip.index');
    Route::post('arsip-digital/upload', [ArchiveController::class, 'store'])->name('arsip.store');
    Route::get('arsip/favorit', [ArchiveController::class, 'favorit'])->name('arsip.favorit');
    Route::get('arsip/kategori', [CategoryController::class, 'index'])->name('category.index');
    
    // === RUTE AKSI ARSIP BARU (Lihat & Unduh) ===
    Route::get('arsip/lihat/{id}', [ArchiveController::class, 'show'])->name('arsip.lihat');
    Route::get('arsip/unduh/{id}', [ArchiveController::class, 'download'])->name('arsip.unduh');
    
    // DISPOSISI & NOTIFIKASI
    Route::get('disposisi', [DispositionController::class, 'index'])->name('disposition.index');
    Route::get('notifikasi', [NotificationController::class, 'index'])->name('notification.index');

    // MANAJEMEN UMUM
    Route::get('manajemen-aset', [AssetController::class, 'index'])->name('asset.index');
    Route::post('manajemen-aset/store', [AssetController::class, 'store'])->name('asset.store');
    Route::get('manajemen-user', [UserController::class, 'index'])->name('user.index');
    Route::post('manajemen-user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('laporan', [ReportController::class, 'index'])->name('report.index');
    Route::get('pengaturan', [SettingController::class, 'index'])->name('setting.index');
});

// ====================================================================
// RUTE STAFF (LIMITED ACCESS)
// ====================================================================
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    
    // ARSIP
    Route::get('arsip-digital', [ArchiveController::class, 'index'])->name('arsip.index');
    Route::post('arsip-digital/upload', [ArchiveController::class, 'store'])->name('arsip.store'); 
    Route::get('arsip/favorit', [ArchiveController::class, 'favorit'])->name('arsip.favorit');
    Route::get('arsip/kategori', [CategoryController::class, 'index'])->name('category.index');
    
    // === RUTE AKSI ARSIP BARU (Lihat & Unduh) ===
    Route::get('arsip/lihat/{id}', [ArchiveController::class, 'show'])->name('arsip.lihat');
    Route::get('arsip/unduh/{id}', [ArchiveController::class, 'download'])->name('arsip.unduh');
    
    // DISPOSISI & NOTIFIKASI
    Route::get('disposisi', [DispositionController::class, 'index'])->name('disposition.index');
    Route::get('notifikasi', [NotificationController::class, 'index'])->name('notification.index');
    
    // MANAJEMEN UMUM
    Route::get('manajemen-aset', [AssetController::class, 'index'])->name('asset.index');
    Route::get('laporan', [ReportController::class, 'index'])->name('report.index');
});

// Redirect user yang mengakses root URL ('/')
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role; 
        
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'staff') {
            // Perlu menggunakan route staff di sini!
            return redirect()->route('staff.dashboard');
        }
    }
    
    return redirect()->route('login');
});