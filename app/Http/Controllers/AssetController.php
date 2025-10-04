<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public function index()
    {
        // Data Placeholder untuk meniru card aset
        $assets = [
            (object)[
                'nama' => 'Laptop Dell Latitude 5520', 'kode' => 'LPT-001-2024', 
                'tipe' => 'Komputer', 'merk' => 'Dell', 'status' => 'available', 
                'serial' => 'DL552001', 'lokasi' => 'Ruang IT', 'unit' => 'Diskominfo', 
                'pembelian' => '10/1/2024', 'spesifikasi' => 'Intel i5, 8GB RAM, 256GB SSD'
            ],
            (object)[
                'nama' => 'Printer HP LaserJet Pro', 'kode' => 'PRT-001-2024', 
                'tipe' => 'Printer', 'merk' => 'HP', 'status' => 'in-use', 
                'serial' => 'HP2024001', 'lokasi' => 'Ruang Administrasi', 'unit' => 'Sekretariat', 
                'pembelian' => '8/1/2024', 'spesifikasi' => 'Laser, A4, Network Ready'
            ],
            (object)[
                'nama' => 'Proyektor Epson EB-X41', 'kode' => 'PRJ-001-2024', 
                'tipe' => 'Proyektor', 'merk' => 'Epson', 'status' => 'maintenance', 
                'serial' => 'EP2024001', 'lokasi' => 'Ruang Rapat', 'unit' => 'Diskominfo', 
                'pembelian' => '5/1/2024', 'spesifikasi' => '3600 Lumens, XGA, HDMI'
            ],
        ];

        // Hitung statistik berdasarkan placeholder
        $stats = [
            'Tersedia' => 1,
            'Digunakan' => 1,
            'Perawatan' => 1,
            'Rusak' => 0,
        ];

        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';
        return view("{$viewPrefix}.aset.index", compact('assets', 'stats'));
    }

    public function store(Request $request) {
        // Logika untuk menyimpan data aset baru ke database (Belum diimplementasikan)
        return redirect()->route('admin.asset.index')->with('success', 'Aset baru berhasil ditambahkan!');
    }

    public function showBorrowForm() {
        // Logika untuk menampilkan form peminjaman aset (Modal)
        return view('admin.aset.borrow-modal'); 
    }
}