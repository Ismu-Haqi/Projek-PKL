<?php

namespace App\Http\Controllers;

use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleDriveBackupController extends Controller
{
    protected GoogleDriveService $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    // ============================================================
    // TEST KONEKSI
    // ============================================================

    public function testConnection()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->driveService->testConnection();

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    // ============================================================
    // BACKUP ARSIP SAJA
    // ============================================================

    public function backupArsip()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $result = $this->driveService->backupArsip();

            Log::info('[GoogleDrive] Backup arsip selesai', [
                'user'    => Auth::user()->name,
                'summary' => $result,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Backup arsip selesai: {$result['success']} berhasil, {$result['skipped']} dilewati, {$result['failed']} gagal dari {$result['total']} file.",
                'result'  => $result,
            ]);

        } catch (\Exception $e) {
            Log::error('[GoogleDrive] Backup arsip error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ============================================================
    // BACKUP DISPOSISI SAJA
    // ============================================================

    public function backupDisposisi()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $result = $this->driveService->backupDisposisi();

            Log::info('[GoogleDrive] Backup disposisi selesai', [
                'user'    => Auth::user()->name,
                'summary' => $result,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Backup bukti disposisi selesai: {$result['success']} berhasil, {$result['skipped']} dilewati, {$result['failed']} gagal dari {$result['total']} file.",
                'result'  => $result,
            ]);

        } catch (\Exception $e) {
            Log::error('[GoogleDrive] Backup disposisi error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ============================================================
    // BACKUP SEMUA (Arsip + Disposisi)
    // ============================================================

    public function backupAll()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $result = $this->driveService->backupAll();

            Log::info('[GoogleDrive] Backup semua selesai', [
                'user'    => Auth::user()->name,
                'summary' => $result['summary'],
            ]);

            $s = $result['summary'];
            return response()->json([
                'success' => true,
                'message' => "Backup selesai: {$s['success']} berhasil, {$s['skipped']} dilewati, {$s['failed']} gagal dari {$s['total']} total file.",
                'result'  => $result,
            ]);

        } catch (\Exception $e) {
            Log::error('[GoogleDrive] Backup semua error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ============================================================
    // STATUS KONFIGURASI
    // ============================================================

    public function status()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $credPath    = config('google-drive.credentials_path');
        $folderId    = config('google-drive.folder_id');
        $autoBackup  = config('google-drive.auto_backup_enabled');

        return response()->json([
            'success'             => true,
            'credentials_exists'  => file_exists($credPath),
            'credentials_path'    => $credPath,
            'folder_id_set'       => !empty($folderId),
            'folder_id'           => $folderId ? substr($folderId, 0, 8) . '...' : '-',
            'auto_backup_enabled' => $autoBackup,
            'is_configured'       => $this->driveService->isConfigured(),
        ]);
    }
}
