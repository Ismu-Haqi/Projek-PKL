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

// ✅ Halaman Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ✅ Redirect Root URL - HANYA SATU KALI
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

// ✅ Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
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
    });
    
    // Menu Lainnya
    Route::get('disposisi', [DispositionController::class, 'index'])->name('disposisi.index');
    Route::get('notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::get('manajemen-aset', [AssetController::class, 'index'])->name('aset.index');
    Route::get('manajemen-user', [UserController::class, 'index'])->name('user.index');
    Route::get('laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
});

// ✅ Staff Routes
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    
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
    });
    
    Route::get('disposisi', [DispositionController::class, 'index'])->name('disposisi.index');
    Route::get('notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::get('manajemen-aset', [AssetController::class, 'index'])->name('aset.index');
});