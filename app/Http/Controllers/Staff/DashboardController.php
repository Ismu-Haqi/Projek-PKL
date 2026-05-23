<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Archive;
use App\Models\Disposition;
use App\Models\Asset;
use App\Models\AssetBorrow;
use App\Models\Notification;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // ================================
        // STATISTIK UNTUK STAFF
        // ================================
        
        // 1. Disposisi yang perlu ditindaklanjuti (pending/in progress)
        $pendingDispositions = 0;
        if (Schema::hasTable('dispositions')) {
            $pendingDispositions = Disposition::where('to_user_id', $user->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->count();
        }
        
        // 2. Arsip yang diupload oleh staff ini
        $myArchivesCount = 0;
        if (Schema::hasTable('archives')) {
            $myArchivesCount = Archive::where('user_id', $user->id)->count();
        }
        
        // 3. Arsip favorit staff
        $favoritesCount = 0;
        if (Schema::hasTable('archives') && Schema::hasColumn('archives', 'is_favorite')) {
            $favoritesCount = Archive::where('user_id', $user->id)
                ->where('is_favorite', true)
                ->count();
        }
        
        // 4. Notifikasi belum dibaca
        $unreadNotifications = 0;
        if (Schema::hasTable('notifications')) {
            $unreadNotifications = Notification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count();
        }
        
        // ================================
        // DATA UNTUK TAMPILAN
        // ================================
        
        // Disposisi terbaru yang perlu ditindaklanjuti (max 3)
        $recentDispositions = [];
        if (Schema::hasTable('dispositions')) {
            $recentDispositions = Disposition::where('to_user_id', $user->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->with(['fromUser', 'disposable']) // Eager loading
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        }
        
        // Arsip terbaru yang diupload staff ini (max 5)
        $recentArchives = [];
        if (Schema::hasTable('archives')) {
            $query = Archive::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5);
                
            if (Schema::hasTable('categories')) {
                $query->with('category');
            }
            
            $recentArchives = $query->get();
        }
        
        // ✅ Aktivitas terkini (kombinasi dari berbagai sumber + ASET)
        $recentActivities = $this->getRecentActivities($user->id);

        // Reminder Perawatan H-7
        $asetJatuhTempoPerawatan = Asset::jatuhTempoPerawatan(7)
            ->get(['id', 'kode_asset', 'nama', 'kategori', 'unit',
                   'jadwal_perawatan_selanjutnya', 'jenis_perawatan']);
        $asetPerawatanTerlambat = Asset::perawatanTerlambat()
            ->get(['id', 'kode_asset', 'nama', 'kategori', 'unit',
                   'jadwal_perawatan_selanjutnya', 'jenis_perawatan']);
        
        return view('staff.dashboard', compact(
            'pendingDispositions',
            'myArchivesCount',
            'favoritesCount',
            'unreadNotifications',
            'recentDispositions',
            'recentArchives',
            'recentActivities',
            'asetJatuhTempoPerawatan',
            'asetPerawatanTerlambat'
        ));
    }
    
    /**
     * ✅ UPDATED: Get recent activities for staff - WITH ASSET ACTIVITIES
     */
    private function getRecentActivities($userId)
    {
        $activities = collect();
        
        // Aktivitas dari arsip (ambil 4)
        if (Schema::hasTable('archives')) {
            $archives = Archive::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get()
                ->map(function($archive) {
                    return [
                        'type' => 'archive_upload',
                        'title' => 'Anda mengunggah arsip baru',
                        'description' => $archive->judul,
                        'time' => $archive->created_at->diffForHumans(),
                        'created_at' => $archive->created_at,
                        'icon' => 'upload',
                        'color' => 'blue'
                    ];
                });
            
            $activities = $activities->merge($archives);
        }
        
        // Aktivitas dari disposisi (ambil 4)
        if (Schema::hasTable('dispositions')) {
            $dispositions = Disposition::where('to_user_id', $userId)
                ->orderBy('updated_at', 'desc')
                ->limit(4)
                ->get()
                ->map(function($disposition) {
                    $activityType = 'disposition_received';
                    $activityTitle = 'Anda menerima disposisi baru';
                    $color = 'orange';
                    
                    if ($disposition->status === 'completed') {
                        $activityType = 'disposition_completed';
                        $activityTitle = 'Disposisi selesai diproses';
                        $color = 'green';
                    } elseif ($disposition->status === 'in_progress') {
                        $activityType = 'disposition_progress';
                        $activityTitle = 'Disposisi sedang dikerjakan';
                        $color = 'blue';
                    }
                    
                    return [
                        'type' => $activityType,
                        'title' => $activityTitle,
                        'description' => $disposition->subject ?? 'Disposisi',
                        'time' => $disposition->updated_at->diffForHumans(),
                        'created_at' => $disposition->updated_at,
                        'icon' => 'disposition',
                        'color' => $color
                    ];
                });
            
            $activities = $activities->merge($dispositions);
        }
        
        // ✅ NEW: Aktivitas dari peminjaman aset (ambil 4)
        if (Schema::hasTable('asset_borrows')) {
            $assetBorrows = AssetBorrow::where('borrower_id', $userId)
                ->with('asset')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get()
                ->map(function($borrow) {
                    $activityType = 'asset_borrow_pending';
                    $activityTitle = 'Anda mengajukan peminjaman aset';
                    $color = 'yellow';
                    
                    if ($borrow->status === 'approved') {
                        $activityType = 'asset_borrow_approved';
                        $activityTitle = 'Peminjaman aset disetujui';
                        $color = 'green';
                    } elseif ($borrow->status === 'borrowed') {
                        $activityType = 'asset_borrowed';
                        $activityTitle = 'Anda meminjam aset';
                        $color = 'blue';
                    } elseif ($borrow->status === 'returned') {
                        $activityType = 'asset_returned';
                        $activityTitle = 'Anda mengembalikan aset';
                        $color = 'purple';
                    } elseif ($borrow->status === 'rejected') {
                        $activityType = 'asset_borrow_rejected';
                        $activityTitle = 'Peminjaman aset ditolak';
                        $color = 'red';
                    } elseif ($borrow->status === 'overdue') {
                        $activityType = 'asset_overdue';
                        $activityTitle = 'Peminjaman aset terlambat';
                        $color = 'red';
                    }
                    
                    return [
                        'type' => $activityType,
                        'title' => $activityTitle,
                        'description' => $borrow->asset->nama ?? 'Aset',
                        'time' => $borrow->created_at->diffForHumans(),
                        'created_at' => $borrow->created_at,
                        'icon' => 'asset',
                        'color' => $color
                    ];
                });
            
            $activities = $activities->merge($assetBorrows);
        }
        
        // ✅ NEW: Aktivitas dari aset yang ditambahkan staff (ambil 3)
        if (Schema::hasTable('assets')) {
            $userAssets = Asset::where('penanggung_jawab', Auth::user()->name)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function($asset) {
                    $isNew = $asset->created_at->diffInHours(Carbon::now()) < 24;
                    
                    return [
                        'type' => $isNew ? 'asset_created' : 'asset_managed',
                        'title' => $isNew ? 'Anda menambahkan aset baru' : 'Aset dikelola',
                        'description' => $asset->nama,
                        'time' => $asset->created_at->diffForHumans(),
                        'created_at' => $asset->created_at,
                        'icon' => 'asset_add',
                        'color' => $isNew ? 'indigo' : 'gray'
                    ];
                });
            
            $activities = $activities->merge($userAssets);
        }
        
        // Aktivitas dari favorit (ambil 2)
        if (Schema::hasTable('archives') && Schema::hasColumn('archives', 'is_favorite')) {
            $favorites = Archive::where('user_id', $userId)
                ->where('is_favorite', true)
                ->orderBy('updated_at', 'desc')
                ->limit(2)
                ->get()
                ->map(function($archive) {
                    return [
                        'type' => 'favorite_added',
                        'title' => 'Arsip ditandai favorit',
                        'description' => $archive->judul,
                        'time' => $archive->updated_at->diffForHumans(),
                        'created_at' => $archive->updated_at,
                        'icon' => 'star',
                        'color' => 'yellow'
                    ];
                });
            
            $activities = $activities->merge($favorites);
        }
        
        // Sort by time and take 10 most recent
        return $activities->sortByDesc('created_at')->take(10)->values();
    }
}