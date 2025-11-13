<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications
     * ✅ UPDATED: Support all roles (admin, staff, pimpinan)
     */
    public function index()
    {
        $role = Auth::user()->role;
        $user = Auth::user();
        
        // Get notifications for current user
        $notifications = Notification::forUser($user->id)
            ->orderBy('read_at', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Statistics
        $stats = [
            'total' => Notification::forUser($user->id)->count(),
            'unread' => Notification::forUser($user->id)->unread()->count(),
            'read' => Notification::forUser($user->id)->read()->count(),
            'today' => Notification::forUser($user->id)->whereDate('created_at', today())->count(),
        ];
        
        return view("{$role}.notifikasi.index", compact('notifications', 'stats'));
    }

    /**
     * Mark notification as read
     * ✅ All roles can mark as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = Notification::forUser($user->id)->findOrFail($id);
        
        $notification->markAsRead();
        
        // Redirect ke URL notifikasi jika ada
        if ($notification->url) {
            return redirect($notification->url);
        }
        
        return back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca');
    }

    /**
     * Mark all notifications as read
     * ✅ All roles can mark all as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::forUser($user->id)
            ->unread()
            ->update(['read_at' => now()]);
        
        return back()->with('success', 'Semua notifikasi ditandai sebagai sudah dibaca');
    }

    /**
     * Delete notification
     * ✅ All roles can delete their notifications
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $notification = Notification::forUser($user->id)->findOrFail($id);
        
        $notification->delete();
        
        return back()->with('success', 'Notifikasi berhasil dihapus');
    }

    /**
     * Get unread notifications count (for AJAX)
     * ✅ All roles can get unread count
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = Notification::forUser($user->id)->unread()->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for dropdown)
     * ✅ All roles can get recent notifications
     */
    public function getRecent()
    {
        $user = Auth::user();
        
        $notifications = Notification::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::forUser($user->id)->unread()->count(),
        ]);
    }

    /**
     * Delete all read notifications
     * ✅ All roles can clear read notifications
     */
    public function clearRead()
    {
        $user = Auth::user();
        
        Notification::forUser($user->id)
            ->read()
            ->delete();
        
        return back()->with('success', 'Notifikasi yang sudah dibaca berhasil dihapus');
    }
}