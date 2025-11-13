<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Menampilkan halaman Manajemen Kategori Arsip.
     * ✅ UPDATED: Support pimpinan (read-only for pimpinan)
     */
    public function index()
    {
        $role = Auth::user()->role;
        
        // ✅ Allow admin, staff, and pimpinan
        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }
        
        // ✅ UPDATED: Support pimpinan
        $viewPrefix = match($role) {
            'admin' => 'admin',
            'pimpinan' => 'pimpinan',
            default => 'staff'
        };
        
        // Data Placeholder (untuk mengisi tabel)
        $categories = [
            (object)['kode' => 'SKP', 'nama' => 'Surat Keputusan', 'desc' => 'Dokumen penetapan kebijakan.', 'status' => 'Aktif'],
            (object)['kode' => 'LPR', 'nama' => 'Laporan Keuangan', 'desc' => 'Dokumen hasil rekonsiliasi dan audit.', 'status' => 'Aktif'],
            (object)['kode' => 'DMT', 'nama' => 'Dokumentasi Rapat', 'desc' => 'Notulensi rapat dan bahan presentasi.', 'status' => 'Aktif'],
        ];

        return view("{$viewPrefix}.arsip.kategori.index", compact('categories')); 
    }

    /**
     * Store a newly created category
     * ✅ ADMIN ONLY
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        // TODO: Implement category store logic
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Update the specified category
     * ✅ ADMIN ONLY
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        // TODO: Implement category update logic
        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified category
     * ✅ ADMIN ONLY
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        // TODO: Implement category delete logic
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}