<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetLocationController extends Controller
{
    /**
     * Daftar ruangan baku sesuai Denah Kantor Diskominfo Batola,
     * dipakai sebagai pilihan cepat "Lokasi" supaya data konsisten
     * dengan penamaan ruangan pada denah.
     */
    public const DAFTAR_RUANGAN = [
        'Ruang IKP (Informasi dan Komunikasi Publik)',
        'Ruang Tamu',
        'Ruang Sekretariat',
        'Ruang Sekretaris dan Sistem Persandian (SP)',
        'Ruang Kepala Dinas',
        'Ruang Tunggu Tamu Kepala Dinas',
        'Aula / Ruang Rapat',
        'Ruang Podcast',
        'Ruang E-Gov (E-Government)',
        'Dapur',
        'Ruang Pengarsipan',
        'WC / Kamar Mandi',
    ];

    // ========================
    // TAMPILAN DENAH (semua role — untuk verifikasi fisik)
    // ========================
    public function index(Request $request)
    {
        $role = Auth::user()->role;

        $query = Asset::sudahDitempatkan();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        $assets = $query->orderBy('nama')->get([
            'id', 'nama', 'kode_asset', 'kategori', 'lokasi', 'status', 'posisi_x', 'posisi_y',
        ]);

        $belumDitempatkanCount = Asset::belumDitempatkan()->count();
        $kategoriList = Asset::whereNotNull('kategori')->distinct()->pluck('kategori');

        return view("{$role}.aset.denah", compact('assets', 'belumDitempatkanCount', 'kategoriList'));
    }

    // ========================
    // HALAMAN KELOLA (Admin — tempatkan/pindahkan pin)
    // ========================
    public function kelola(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $filter = $request->get('filter', 'belum'); // belum | sudah | semua

        $query = Asset::query();
        if ($filter === 'belum') {
            $query->belumDitempatkan();
        } elseif ($filter === 'sudah') {
            $query->sudahDitempatkan();
        }

        $assets = $query->orderBy('nama')->get([
            'id', 'nama', 'kode_asset', 'kategori', 'lokasi', 'status', 'posisi_x', 'posisi_y',
        ]);

        $ruanganList = self::DAFTAR_RUANGAN;

        return view('admin.aset.denah-kelola', compact('assets', 'filter', 'ruanganList'));
    }

    // ========================
    // SIMPAN POSISI PIN (AJAX, Admin)
    // ========================
    public function simpanPosisi(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'posisi_x' => 'required|numeric|min:0|max:100',
            'posisi_y' => 'required|numeric|min:0|max:100',
            'lokasi'   => 'nullable|string|max:255',
        ]);

        $asset = Asset::findOrFail($id);
        $asset->posisi_x = $request->posisi_x;
        $asset->posisi_y = $request->posisi_y;
        if ($request->filled('lokasi')) {
            $asset->lokasi = $request->lokasi;
        }
        $asset->save();

        return response()->json([
            'success' => true,
            'message' => "Posisi \"{$asset->nama}\" berhasil disimpan.",
        ]);
    }

    // ========================
    // HAPUS POSISI PIN (Admin)
    // ========================
    public function hapusPosisi($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $asset = Asset::findOrFail($id);
        $asset->update(['posisi_x' => null, 'posisi_y' => null]);

        return response()->json(['success' => true, 'message' => "Posisi \"{$asset->nama}\" dihapus dari denah."]);
    }
}
