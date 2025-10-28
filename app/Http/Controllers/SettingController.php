<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $role = Auth::user()->role;
        $viewPrefix = $role === 'admin' ? 'admin' : 'staff';
        
        // Get all settings as array
        $settings = [];
        $allSettings = Setting::all();
        
        foreach ($allSettings as $setting) {
            $settings[$setting->key] = $setting->value;
        }
        
        return view("{$viewPrefix}.pengaturan.index", compact('settings'));
    }

    /**
     * Update user profile
     */
/**
 * Update user profile (with avatar support)
 */
/**
 * Update user profile (with avatar support)
 */
public function updateProfil(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        'current_password' => 'nullable|required_with:password',
        'password' => 'nullable|min:8|confirmed',
    ], [
        'name.required' => 'Nama harus diisi',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah digunakan',
        'avatar.image' => 'File harus berupa gambar',
        'avatar.mimes' => 'Format gambar harus: jpeg, jpg, png, atau gif',
        'avatar.max' => 'Ukuran gambar maksimal 2MB',
        'current_password.required_with' => 'Password saat ini harus diisi',
        'password.min' => 'Password minimal 8 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
    ]);

    try {
        // Update name and email
        $user->name = $request->name;
        $user->email = $request->email;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar in avatars folder
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Update password if provided
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Password saat ini salah'])
                    ->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
        
    } catch (\Exception $e) {
        return back()
            ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
            ->withInput();
    }
}

/**
 * Remove user avatar
 */
public function removeAvatar()
{
    try {
        $user = Auth::user();
        
        if ($user->avatar) {
            // Delete avatar file from storage
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Update user record
            $user->avatar = null;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada foto untuk dihapus'
        ], 400);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus foto: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Update general settings (Admin only)
     */
    public function update(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized access');
        }

        try {
            $settingsToUpdate = [
                'app_name',
                'timezone',
                'items_per_page',
                'date_format',
            ];

            foreach ($settingsToUpdate as $key) {
                if ($request->has($key)) {
                    Setting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $request->input($key)]
                    );
                }
            }

            // Handle checkboxes (they won't be in request if unchecked)
            $checkboxSettings = [
                'enable_registration',
                'enable_notifications',
                'maintenance_mode',
            ];

            foreach ($checkboxSettings as $key) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->has($key) ? '1' : '0']
                );
            }

            return back()->with('success', 'Pengaturan sistem berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Clear cache (Admin only)
     */
    public function clearCache()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dibersihkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create database backup (Admin only)
     */
    public function backup()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $path = storage_path('app/backups');
            
            // Create backups directory if not exists
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $fullPath = $path . '/' . $filename;

            // Database credentials
            $dbHost = env('DB_HOST', '127.0.0.1');
            $dbPort = env('DB_PORT', '3306');
            $dbName = env('DB_DATABASE');
            $dbUser = env('DB_USERNAME');
            $dbPass = env('DB_PASSWORD');

            // Check if mysqldump is available
            $mysqldumpPath = $this->findMysqldump();
            
            if (!$mysqldumpPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'mysqldump tidak ditemukan. Install MySQL client terlebih dahulu.'
                ], 500);
            }

            // Build mysqldump command
            $command = sprintf(
                '%s --user=%s --password=%s --host=%s --port=%s %s > %s 2>&1',
                $mysqldumpPath,
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbName),
                escapeshellarg($fullPath)
            );

            // Execute backup
            exec($command, $output, $returnVar);

            // Check if backup was successful
            if ($returnVar !== 0 || !file_exists($fullPath) || filesize($fullPath) === 0) {
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat backup. Error: ' . implode("\n", $output)
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dibuat',
                'filename' => $filename,
                'size' => $this->formatFileSize(filesize($fullPath))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of backups (Admin only)
     */
    public function backupList()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $path = storage_path('app/backups');
            $backups = [];

            if (file_exists($path)) {
                $files = glob($path . '/*.sql');
                
                foreach ($files as $file) {
                    $backups[] = [
                        'name' => basename($file),
                        'size' => $this->formatFileSize(filesize($file)),
                        'date' => date('d/m/Y H:i:s', filemtime($file)),
                        'timestamp' => filemtime($file)
                    ];
                }

                // Sort by timestamp descending (newest first)
                usort($backups, function($a, $b) {
                    return $b['timestamp'] - $a['timestamp'];
                });
            }

            return response()->json([
                'success' => true,
                'backups' => $backups
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat daftar backup: ' . $e->getMessage()
            ], 500);
        }
    }

/**
 * Update appearance settings (Admin only)
 */
public function updateAppearance(Request $request)
{
    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized access');
    }

    try {
        $appearanceSettings = [
            'theme',
            'accent_color',
            'sidebar_style',
            'navbar_position',
            'font_size',
            'animation_speed',
            'text_size', // NEW: Text size setting
        ];

        // Update regular settings
        foreach ($appearanceSettings as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->input($key)]
                );
            }
        }

        // Handle checkboxes (boolean settings)
        $checkboxSettings = [
            'show_breadcrumbs',
            'show_notifications',
            'compact_mode',
            'smooth_scrolling',
        ];

        foreach ($checkboxSettings as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->has($key) ? '1' : '0']
            );
        }

        // Clear appearance cache
        cache()->forget('appearance_settings');
        
        // Clear other caches
        \Artisan::call('view:clear');
        \Artisan::call('cache:clear');

        return back()->with('success', 'Pengaturan tampilan berhasil diperbarui! Refresh halaman (Ctrl+Shift+R) untuk melihat perubahan.');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal memperbarui pengaturan tampilan: ' . $e->getMessage());
    }
}
    /**
     * Download backup file (Admin only)
     */
    public function downloadBackup($filename)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'File backup tidak ditemukan');
        }

        return response()->download($path);
    }

    /**
     * Find mysqldump executable path
     */
    private function findMysqldump()
    {
        $possiblePaths = [
            'mysqldump', // System PATH
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/opt/homebrew/bin/mysqldump',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
        ];

        foreach ($possiblePaths as $path) {
            $checkCommand = strpos(PHP_OS, 'WIN') === 0 ? "where $path" : "which $path";
            exec($checkCommand, $output, $returnVar);
            
            if ($returnVar === 0) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Format file size to human readable
     */
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}