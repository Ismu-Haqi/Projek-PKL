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
     * ✅ UPDATED: Support all roles (admin, staff, pimpinan)
     */
    public function index()
    {
        $role = Auth::user()->role;
        
        // ✅ All authenticated users can access settings
        $viewPrefix = match($role) {
            'admin' => 'admin',
            'pimpinan' => 'pimpinan',
            default => 'staff'
        };
        
        // Get all settings as array
        $settings = [];
        $allSettings = Setting::all();
        
        foreach ($allSettings as $setting) {
            $settings[$setting->key] = $setting->value;
        }
        
        return view("{$viewPrefix}.pengaturan.index", compact('settings'));
    }

    /**
     * Update user profile (with avatar support)
     * ✅ All roles can update their profile
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'phone.max' => 'Nomor WhatsApp maksimal 20 karakter',
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
            $user->phone = $request->phone; // ✅ TAMBAHAN BARU (Poin 5 revisi) - nomor WhatsApp

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
     * ✅ All roles can remove their avatar
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
     * Update general settings
     * ✅ ADMIN ONLY
     */
    public function update(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized access - Admin only');
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
     * Update appearance settings
     * ✅ UPDATED: All roles can update appearance
     */
    public function updateAppearance(Request $request)
    {
        try {
            $user = Auth::user();
            
            $appearanceSettings = [
                'theme',
                'accent_color',
                'sidebar_style',
                'navbar_position',
                'font_size',
                'animation_speed',
                'text_size',
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

            return back()->with('success', 'Pengaturan tampilan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan tampilan: ' . $e->getMessage());
        }
    }

    /**
     * Clear cache
     * ✅ ADMIN ONLY
     */
    public function clearCache()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized - Admin only'], 403);
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
     * Create database backup
     * ✅ ADMIN ONLY — Mendukung mysqldump (jika tersedia) + fallback PHP-native
     */
    public function backup()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized - Admin only'], 403);
        }

        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $path = storage_path('app/backups');

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $fullPath = $path . '/' . $filename;

            // Coba mysqldump dulu
            $mysqldumpPath = $this->findMysqldump();

            if ($mysqldumpPath) {
                $success = $this->backupViaMysqldump($mysqldumpPath, $fullPath);
            } else {
                $success = false;
            }

            // Fallback: PHP-native backup (tanpa mysqldump)
            if (!$success) {
                $this->backupViaPHP($fullPath);
            }

            if (!file_exists($fullPath) || filesize($fullPath) === 0) {
                if (file_exists($fullPath)) unlink($fullPath);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat backup. Pastikan izin folder storage/app/backups dapat ditulis.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dibuat' . ($mysqldumpPath ? '' : ' (mode PHP-native)'),
                'filename' => $filename,
                'size'     => $this->formatFileSize(filesize($fullPath)),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Backup via mysqldump — return true jika berhasil
     */
    private function backupViaMysqldump(string $mysqldumpPath, string $fullPath): bool
    {
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        // Tulis password ke file sementara supaya tidak tampil di process list
        $cnfFile = tempnam(sys_get_temp_dir(), 'mysql_');
        file_put_contents($cnfFile, "[client]\npassword=" . addslashes($dbPass) . "\n");
        chmod($cnfFile, 0600);

        $command = sprintf(
            '%s --defaults-extra-file=%s --user=%s --host=%s --port=%s --single-transaction --routines --triggers --add-drop-table %s > %s 2>&1',
            escapeshellcmd($mysqldumpPath),
            escapeshellarg($cnfFile),
            escapeshellarg($dbUser),
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbName),
            escapeshellarg($fullPath)
        );

        exec($command, $output, $returnVar);
        @unlink($cnfFile);

        return $returnVar === 0 && file_exists($fullPath) && filesize($fullPath) > 0;
    }

    /**
     * Backup PHP-native — menggunakan PDO langsung, tanpa mysqldump
     */
    private function backupViaPHP(string $fullPath): void
    {
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $pdo = new \PDO(
            "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4",
            $dbUser,
            $dbPass,
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );

        $output  = "-- GANDARIA Database Backup\n";
        $output .= "-- Dibuat: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Host: {$dbHost} | Database: {$dbName}\n";
        $output .= "-- -----------------------------------------------\n\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $output .= "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n";
        $output .= "SET NAMES utf8mb4;\n\n";

        // Ambil semua tabel
        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            // CREATE TABLE statement
            $createRow = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_NUM);
            $output .= "\n-- Tabel: `{$table}`\n";
            $output .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $output .= $createRow[1] . ";\n\n";

            // INSERT data
            $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $cols = '`' . implode('`, `', array_keys($rows[0])) . '`';
                foreach ($rows as $row) {
                    $vals = array_map(function ($v) use ($pdo) {
                        return $v === null ? 'NULL' : $pdo->quote($v);
                    }, array_values($row));
                    $output .= "INSERT INTO `{$table}` ({$cols}) VALUES (" . implode(', ', $vals) . ");\n";
                }
                $output .= "\n";
            }
        }

        $output .= "\nSET FOREIGN_KEY_CHECKS=1;\n";
        $output .= "-- Backup selesai: " . date('Y-m-d H:i:s') . "\n";

        file_put_contents($fullPath, $output);
    }

    /**
     * Get list of backups
     * ✅ ADMIN ONLY
     */
    public function backupList()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized - Admin only'], 403);
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
     * Delete a backup file
     * ✅ ADMIN ONLY
     */
    public function deleteBackup($filename)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized - Admin only'], 403);
        }

        // Sanitasi: hanya izinkan nama file .sql tanpa path traversal
        $filename = basename($filename);
        if (!preg_match('/^backup_[\d_]+\.sql$/', $filename)) {
            return response()->json(['success' => false, 'message' => 'Nama file tidak valid.'], 400);
        }

        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            return response()->json(['success' => false, 'message' => 'File tidak ditemukan.'], 404);
        }

        unlink($path);

        return response()->json(['success' => true, 'message' => 'File backup berhasil dihapus.']);
    }

    /**
     * Download backup file
     * ✅ ADMIN ONLY
     */
    public function downloadBackup($filename)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
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