<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';
        
        // Data Placeholder minimum
        $notifications = [(object)['id' => 1, 'pesan' => 'Arsip baru diunggah', 'waktu' => '5 menit lalu']];

        // Memuat view di resources/views/{admin/staff}/notifikasi/index.blade.php
        return view("{$viewPrefix}.notifikasi.index", compact('notifications'));
    }
}