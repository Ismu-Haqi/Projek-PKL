<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Archive;
use App\Models\Disposition;
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
        // 🔥 PERBAIKAN: Pastikan relasi dan data lengkap terload
        $recentDispositions = [];
        if (Schema::hasTable('dispositions')) {
            $recentDispositions = Disposition::where('to_user_id', $user->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->with(['fromUser', 'archive']) // Eager loading
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
        
        // Aktivitas terkini (kombinasi dari berbagai sumber)
        $recentActivities = $this->getRecentActivities($user->id);
        
        return view('staff.dashboard', compact(
            'pendingDispositions',
            'myArchivesCount',
            'favoritesCount',
            'unreadNotifications',
            'recentDispositions',
            'recentArchives',
            'recentActivities'
        ));
    }
    
    /**
     * Get recent activities for staff
     */
    private function getRecentActivities($userId)
    {
        $activities = collect();
        
        // Aktivitas dari arsip
        if (Schema::hasTable('archives')) {
            $archives = Archive::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(3)
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
        
        // Aktivitas dari disposisi
        if (Schema::hasTable('dispositions')) {
            $dispositions = Disposition::where('to_user_id', $userId)
                ->where('status', 'completed')
                ->orderBy('updated_at', 'desc')
                ->limit(2)
                ->get()
                ->map(function($disposition) {
                    return [
                        'type' => 'disposition_completed',
                        'title' => 'Disposisi selesai diproses',
                        'description' => $disposition->subject ?? 'Disposisi',
                        'time' => $disposition->updated_at->diffForHumans(),
                        'created_at' => $disposition->updated_at,
                        'icon' => 'check',
                        'color' => 'green'
                    ];
                });
            
            $activities = $activities->merge($dispositions);
        }
        
        // Aktivitas dari favorit
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
        
        // Sort by time and take 5 most recent
        return $activities->sortByDesc('created_at')->take(5)->values();
    }
}