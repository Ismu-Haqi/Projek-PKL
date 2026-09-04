<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\RetentionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RetentionScheduleController extends Controller
{
    /**
     * Daftar Jadwal Retensi Arsip (JRA).
     * Admin & Pimpinan bisa lihat; hanya Admin yang bisa kelola.
     */
    public function index()
    {
        $role = Auth::user()->role;

        if (!in_array($role, ['admin', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }

        $schedules = RetentionSchedule::with('category')
            ->orderBy('category_id')
            ->get();

        // Kategori yang belum punya aturan JRA — perlu diingatkan ke admin
        $kategoriBelumDiatur = Category::whereDoesntHave('retentionSchedule')
            ->active()
            ->get();

        return view("{$role}.retensi.index", compact('schedules', 'kategoriBelumDiatur'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $categories = Category::whereDoesntHave('retentionSchedule')->active()->get();

        if ($categories->isEmpty()) {
            return redirect()->route('admin.retensi.index')
                ->with('info', 'Semua kategori arsip sudah memiliki aturan Jadwal Retensi Arsip.');
        }

        return view('admin.retensi.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $validated = $request->validate([
            'category_id'            => 'required|exists:categories,id|unique:retention_schedules,category_id',
            'kode_klasifikasi'       => 'nullable|string|max:50',
            'retensi_aktif_tahun'    => 'required|integer|min:0|max:100',
            'retensi_inaktif_tahun'  => 'required|integer|min:0|max:100',
            'nasib_akhir'            => 'required|in:musnah,permanen,dinilai_kembali',
            'dasar_hukum'            => 'nullable|string|max:255',
            'keterangan'             => 'nullable|string',
        ], [
            'category_id.unique' => 'Kategori ini sudah memiliki aturan Jadwal Retensi Arsip.',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = true;

        $schedule = RetentionSchedule::create($validated);

        // Terapkan otomatis ke arsip lama dari kategori ini yang belum
        // pernah dihitung retensinya berdasarkan JRA (tidak menimpa yang
        // sudah diatur manual sebelumnya).
        $this->terapkanKeArsipLama($schedule);

        return redirect()->route('admin.retensi.index')
            ->with('success', 'Jadwal Retensi Arsip berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $schedule = RetentionSchedule::with('category')->findOrFail($id);

        return view('admin.retensi.edit', compact('schedule'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $schedule = RetentionSchedule::findOrFail($id);

        $validated = $request->validate([
            'kode_klasifikasi'       => 'nullable|string|max:50',
            'retensi_aktif_tahun'    => 'required|integer|min:0|max:100',
            'retensi_inaktif_tahun'  => 'required|integer|min:0|max:100',
            'nasib_akhir'            => 'required|in:musnah,permanen,dinilai_kembali',
            'dasar_hukum'            => 'nullable|string|max:255',
            'keterangan'             => 'nullable|string',
            'is_active'              => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $schedule->update($validated);

        // Hitung ulang retensi untuk semua arsip yang memakai aturan ini,
        // supaya perubahan aturan langsung tercermin di data arsip.
        $this->hitungUlangArsipTerkait($schedule);

        return redirect()->route('admin.retensi.index')
            ->with('success', 'Jadwal retensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized - Admin only');
        }

        $schedule = RetentionSchedule::findOrFail($id);

        if ($schedule->archives()->exists()) {
            return back()->with('error', 'Tidak bisa menghapus: aturan ini masih dipakai oleh arsip yang tersimpan. Nonaktifkan saja jika sudah tidak berlaku.');
        }

        $schedule->delete();

        return redirect()->route('admin.retensi.index')
            ->with('success', 'Jadwal Retensi Arsip berhasil dihapus.');
    }

    /**
     * Terapkan aturan JRA baru ke arsip lama dari kategori yang sama yang
     * belum pernah dikaitkan ke aturan JRA manapun.
     */
    private function terapkanKeArsipLama(RetentionSchedule $schedule): void
    {
        $arsipList = $schedule->category->archives()
            ->whereNull('retention_schedule_id')
            ->get();

        foreach ($arsipList as $arsip) {
            $tanggalDasar = $arsip->tanggal_arsip ?? $arsip->created_at;

            $arsip->update([
                'retention_schedule_id' => $schedule->id,
                'tanggal_inaktif'       => $schedule->hitungTanggalInaktif($tanggalDasar),
                'tanggal_retensi'       => $schedule->hitungTanggalRetensi($tanggalDasar),
                'nasib_akhir_arsip'     => $schedule->nasib_akhir,
                'retensi_notif_mendekati_terkirim'   => false,
                'retensi_notif_kedaluwarsa_terkirim' => false,
            ]);
        }
    }

    /**
     * Hitung ulang tanggal retensi seluruh arsip yang terkait aturan JRA ini
     * (dipanggil setelah aturan JRA diedit).
     */
    private function hitungUlangArsipTerkait(RetentionSchedule $schedule): void
    {
        foreach ($schedule->archives()->get() as $arsip) {
            $tanggalDasar = $arsip->tanggal_arsip ?? $arsip->created_at;

            $arsip->update([
                'tanggal_inaktif'   => $schedule->hitungTanggalInaktif($tanggalDasar),
                'tanggal_retensi'   => $schedule->hitungTanggalRetensi($tanggalDasar),
                'nasib_akhir_arsip' => $schedule->nasib_akhir,
                'retensi_notif_mendekati_terkirim'   => false,
                'retensi_notif_kedaluwarsa_terkirim' => false,
            ]);
        }
    }
}
