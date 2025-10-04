<?php

namespace App\Http\Controllers;

use App\Models\Archive; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth;


class ArchiveController extends Controller
{
    /**
     * Menampilkan halaman utama Arsip Digital (Index).
     */
    public function index()
    {
        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';
        
        $archives = [
            (object)['id' => 'ARS001', 'judul' => 'Surat Keputusan Kepala Dinas No. 001/2024', 'kategori' => 'Surat Keputusan', 'unit' => 'Diskominfo', 'tipe' => 'PDF', 'ukuran' => '2.5 MB', 'tanggal' => '15/1/2024', 'favorite' => true],
            (object)['id' => 'ARS002', 'judul' => 'Laporan Keuangan Q4 2023', 'kategori' => 'Laporan Keuangan', 'unit' => 'Bidang Keuangan', 'tipe' => 'Excel', 'ukuran' => '5.2 MB', 'tanggal' => '14/1/2024', 'favorite' => false],
            (object)['id' => 'ARS003', 'judul' => 'Dokumentasi Rapat Koordinasi', 'kategori' => 'Dokumentasi', 'unit' => 'Sekretariat', 'tipe' => 'Word', 'ukuran' => '1.8 MB', 'tanggal' => '13/1/2024', 'favorite' => false],
        ];

        return view("{$viewPrefix}.arsip.index", compact('archives'));
    }

    //----------------------------------------------------------------------
    
    /**
     * Menampilkan halaman Arsip Favorit.
     */
    public function favorit()
    {
        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';

        $favoriteArchives = [
            (object)[
                'id' => 1, // Menggunakan ID numerik untuk rute
                'judul' => 'Laporan Kinerja Infrastruktur TIK Tahun 2024', 
                'nomor_dokumen' => 'LK/IF/001/2024', 
                'deskripsi_singkat' => 'Laporan kinerja tahunan Bidang Infrastruktur TIK Diskominfo Barito Kuala 2024.', 
                'kategori' => 'Laporan Tahunan', 
                'tipe' => 'PDF',
                'tanggal_dokumen' => '2025-01-15', 
                'pengunggah' => 'Admin Diskominfo',
                'unit' => 'Kepala Dinas',
                'file_name' => 'laporan_kinerja_2024.pdf',
                'file_size' => '1.95 MB',
            ],
            (object)[
                'id' => 2, // Menggunakan ID numerik untuk rute
                'judul' => 'RAB Pembangunan Jaringan FO Kabupaten', 
                'nomor_dokumen' => 'PR/003/2025', 
                'deskripsi_singkat' => 'Rencana Anggaran Biaya dan proposal upgrade jaringan fiber optik kabupaten.', 
                'kategori' => 'Proyek TIK', 
                'tipe' => 'DOCX',
                'tanggal_dokumen' => '2025-02-15', 
                'pengunggah' => 'Budi Santoso',
                'unit' => 'Bidang Infrastruktur',
                'file_name' => 'proposal_upgrade_jaringan.docx',
                'file_size' => '2.93 MB',
            ],
        ];

        return view("{$viewPrefix}.arsip.favorit", compact('favoriteArchives'));
    }

    //----------------------------------------------------------------------

    /**
     * Menyimpan arsip yang baru diunggah.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_dokumen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|string',
            'unit' => 'required|string',
            'tanggal_dokumen' => 'required|date',
            'file_arsip' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $filePath = null;

        if ($request->hasFile('file_arsip')) {
            $file = $request->file('file_arsip');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/archives', $fileName); 
        }

        Archive::create([
            'user_id' => Auth::id(), 
            'nomor_surat' => 'TEMP-' . Str::upper(Str::random(8)), 
            'judul' => $request->judul_dokumen,
            'keterangan' => $request->deskripsi,
            'jenis_arsip' => $request->kategori,
            'unit' => $request->unit,
            'tanggal_arsip' => $request->tanggal_dokumen,
            'file_path' => $filePath, 
        ]);

        return redirect()->route('admin.arsip.index')->with('success', 'Arsip baru berhasil diunggah!');
    }

    //----------------------------------------------------------------------
    // === METHOD BARU UNTUK RUTE LIHAT DAN UNDUH ===
    //----------------------------------------------------------------------

    /**
     * Menampilkan detail (show) arsip berdasarkan ID.
     */
    public function show($id)
    {
        // Dalam aplikasi nyata, Anda akan mencari arsip dari database:
        // $archive = Archive::findOrFail($id);
        
        return view('admin.arsip.view_detail', ['archiveId' => $id, 'message' => "Halaman detail untuk Arsip ID: {$id}"]);
    }

    /**
     * Mengunduh (download) arsip berdasarkan ID.
     */
    public function download($id)
    {
        // Dalam aplikasi nyata, Anda akan mencari path file dari database:
        // $archive = Archive::findOrFail($id);
        // $filePath = storage_path('app/' . $archive->file_path);

        // Placeholder untuk demonstrasi
        $dummyPath = storage_path('app/public/dummy.txt');
        
        // Buat file dummy jika tidak ada
        if (!Storage::exists('public/dummy.txt')) {
             Storage::put('public/dummy.txt', 'Ini adalah file dummy untuk Arsip ID: ' . $id);
        }

        return response()->download($dummyPath, 'arsip_gandaria_' . $id . '.txt');
    }
}