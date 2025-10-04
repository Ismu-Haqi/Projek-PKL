<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Menampilkan halaman Manajemen Kategori Arsip.
     */
    public function index()
    {
        // 1. Ambil prefix view: 'admin' atau 'staff'
        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';
        
        // 2. Data Placeholder (untuk mengisi tabel)
        $categories = [
            (object)['kode' => 'SKP', 'nama' => 'Surat Keputusan', 'desc' => 'Dokumen penetapan kebijakan.', 'status' => 'Aktif'],
            (object)['kode' => 'LPR', 'nama' => 'Laporan Keuangan', 'desc' => 'Dokumen hasil rekonsiliasi dan audit.', 'status' => 'Aktif'],
            (object)['kode' => 'DMT', 'nama' => 'Dokumentasi Rapat', 'desc' => 'Notulensi rapat dan bahan presentasi.', 'status' => 'Aktif'],
        ];

        // 3. Muat view dari resources/views/{admin/staff}/arsip/kategori/index.blade.php
        return view("{$viewPrefix}.arsip.kategori.index", compact('categories')); 
    }
}