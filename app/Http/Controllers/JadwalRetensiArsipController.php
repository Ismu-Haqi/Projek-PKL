<?php

namespace App\Http\Controllers;

use App\Models\JadwalRetensiArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalRetensiArsipController extends Controller
{
    // ========================
    // INDEX
    // ========================
    public function index()
    {
        $daftar = JadwalRetensiArsip::withCount('arsip')->orderBy('kode_klasifikasi')->get();
        return view('admin.jadwal-retensi.index', compact('daftar'));
    }

    // ========================
    // CREATE
    // ========================
    public function create()
    {
        return view('admin.jadwal-retensi.create');
    }

    // ========================
    // STORE
    // ========================
    public function store(Request $request)
    {
        $request->validate([
            'kode_klasifikasi'     => 'required|string|max:20|unique:jadwal_retensi_arsip,kode_klasifikasi',
            'nama_klasifikasi'     => 'required|string|max:255',
            'deskripsi'            => 'nullable|string',
            'jangka_aktif_tahun'   => 'required|integer|min:0|max:100',
            'jangka_inaktif_tahun' => 'required|integer|min:0|max:100',
            'nasib_akhir'          => 'required|in:musnah,permanen,dinilai_kembali',
        ]);

        JadwalRetensiArsip::create([
            'kode_klasifikasi'     => $request->kode_klasifikasi,
            'nama_klasifikasi'     => $request->nama_klasifikasi,
            'deskripsi'            => $request->deskripsi,
            'jangka_aktif_tahun'   => $request->jangka_aktif_tahun,
            'jangka_inaktif_tahun' => $request->jangka_inaktif_tahun,
            'nasib_akhir'          => $request->nasib_akhir,
            'aktif'                => true,
        ]);

        return redirect()->route('admin.jadwal-retensi.index')
            ->with('success', 'Klasifikasi Jadwal Retensi Arsip berhasil ditambahkan.');
    }

    // ========================
    // EDIT
    // ========================
    public function edit($id)
    {
        $jra = JadwalRetensiArsip::findOrFail($id);
        return view('admin.jadwal-retensi.edit', compact('jra'));
    }

    // ========================
    // UPDATE
    // ========================
    public function update(Request $request, $id)
    {
        $jra = JadwalRetensiArsip::findOrFail($id);

        $request->validate([
            'kode_klasifikasi'     => 'required|string|max:20|unique:jadwal_retensi_arsip,kode_klasifikasi,' . $jra->id,
            'nama_klasifikasi'     => 'required|string|max:255',
            'deskripsi'            => 'nullable|string',
            'jangka_aktif_tahun'   => 'required|integer|min:0|max:100',
            'jangka_inaktif_tahun' => 'required|integer|min:0|max:100',
            'nasib_akhir'          => 'required|in:musnah,permanen,dinilai_kembali',
        ]);

        $jra->update([
            'kode_klasifikasi'     => $request->kode_klasifikasi,
            'nama_klasifikasi'     => $request->nama_klasifikasi,
            'deskripsi'            => $request->deskripsi,
            'jangka_aktif_tahun'   => $request->jangka_aktif_tahun,
            'jangka_inaktif_tahun' => $request->jangka_inaktif_tahun,
            'nasib_akhir'          => $request->nasib_akhir,
            'aktif'                => $request->has('aktif'),
        ]);

        // Hitung ulang tanggal_retensi seluruh arsip yang memakai klasifikasi ini,
        // supaya tetap sinkron kalau jangka waktu aktifnya diubah.
        foreach ($jra->arsip as $arsip) {
            if ($arsip->tanggal_arsip) {
                $arsip->update([
                    'tanggal_retensi' => $arsip->tanggal_arsip->copy()->addYears($jra->jangka_aktif_tahun),
                    'retensi_notif_mendekati_terkirim'   => false,
                    'retensi_notif_kedaluwarsa_terkirim' => false,
                ]);
            }
        }

        return redirect()->route('admin.jadwal-retensi.index')
            ->with('success', 'Klasifikasi berhasil diperbarui. Tanggal retensi arsip terkait ikut disesuaikan.');
    }

    // ========================
    // DESTROY
    // ========================
    public function destroy($id)
    {
        $jra = JadwalRetensiArsip::withCount('arsip')->findOrFail($id);

        if ($jra->arsip_count > 0) {
            return back()->with('error', "Klasifikasi ini masih dipakai oleh {$jra->arsip_count} arsip, tidak dapat dihapus. Nonaktifkan saja kalau tidak dipakai lagi.");
        }

        $jra->delete();
        return back()->with('success', 'Klasifikasi berhasil dihapus.');
    }
}
