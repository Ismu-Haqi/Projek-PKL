<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMutation;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AssetMutationController extends Controller
{
    // ========================
    // INDEX
    // ========================
    public function index(Request $request)
    {
        $role = Auth::user()->role;

        $query = AssetMutation::with(['asset', 'pengaju', 'penyetuju'])
            ->orderBy('created_at', 'desc');

        if ($role === 'staff') {
            $query->where('diajukan_oleh', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_mutasi', 'like', "%{$request->search}%")
                  ->orWhereHas('asset', fn($a) => $a->where('nama', 'like', "%{$request->search}%")
                      ->orWhere('kode_asset', 'like', "%{$request->search}%"));
            });
        }

        $mutations = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => AssetMutation::count(),
            'menunggu'  => AssetMutation::menunggu()->count(),
            'disetujui' => AssetMutation::disetujui()->count(),
            'ditolak'   => AssetMutation::where('status', 'ditolak')->count(),
        ];

        $units = Asset::whereNotNull('unit')->distinct()->orderBy('unit')->pluck('unit', 'unit');

        return view("{$role}.mutasi.index", compact('mutations', 'stats', 'units'));
    }

    // ========================
    // CREATE
    // ========================
    public function create()
    {
        $role   = Auth::user()->role;
        $assets = Asset::orderBy('nama')->get(['id', 'nama', 'kode_asset', 'unit', 'lokasi']);
        $units  = Asset::whereNotNull('unit')->distinct()->orderBy('unit')->pluck('unit', 'unit');

        return view("{$role}.mutasi.create", compact('assets', 'units'));
    }

    // ========================
    // STORE
    // ========================
    public function store(Request $request)
    {
        $role = Auth::user()->role;

        $request->validate([
            'asset_id'       => 'required|exists:assets,id',
            'unit_tujuan'    => 'required|string|max:255',
            'lokasi_tujuan'  => 'nullable|string|max:255',
            'tanggal_mutasi' => 'required|date',
            'alasan'         => 'required|string|min:10',
            'berita_acara'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'asset_id.required'       => 'Pilih aset yang akan dimutasi.',
            'unit_tujuan.required'    => 'Unit tujuan wajib diisi.',
            'tanggal_mutasi.required' => 'Tanggal mutasi wajib diisi.',
            'alasan.required'         => 'Alasan mutasi wajib diisi.',
            'alasan.min'              => 'Alasan minimal 10 karakter.',
        ]);

        $asset = Asset::findOrFail($request->asset_id);

        if ($asset->unit && $asset->unit === $request->unit_tujuan) {
            return back()->withErrors(['unit_tujuan' => 'Unit tujuan tidak boleh sama dengan unit asal aset.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $beritaAcaraPath = null;
            if ($request->hasFile('berita_acara')) {
                $beritaAcaraPath = $request->file('berita_acara')->store('mutasi/berita-acara', 'public');
            }

            $mutation = AssetMutation::create([
                'nomor_mutasi'   => AssetMutation::generateNomor(),
                'asset_id'       => $asset->id,
                'unit_asal'      => $asset->unit ?? '-',
                'lokasi_asal'    => $asset->lokasi,
                'unit_tujuan'    => $request->unit_tujuan,
                'lokasi_tujuan'  => $request->lokasi_tujuan,
                'diajukan_oleh'  => Auth::id(),
                'tanggal_mutasi' => $request->tanggal_mutasi,
                'status'         => 'menunggu',
                'alasan'         => $request->alasan,
                'berita_acara'   => $beritaAcaraPath,
            ]);

            // Notifikasi ke admin jika pengaju bukan admin
            if ($role !== 'admin') {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title'   => 'Pengajuan Mutasi Aset Baru',
                        'message' => "Mutasi aset \"{$asset->nama}\" ({$mutation->nomor_mutasi}) diajukan oleh " . Auth::user()->name . " ke unit {$request->unit_tujuan}.",
                        'type'    => 'info',
                        'is_read' => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route("{$role}.mutasi.index")
                ->with('success', "Pengajuan mutasi {$mutation->nomor_mutasi} berhasil dibuat dan menunggu persetujuan.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan mutasi: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan, silakan coba lagi.')->withInput();
        }
    }

    // ========================
    // SHOW
    // ========================
    public function show($id)
    {
        $role     = Auth::user()->role;
        $mutation = AssetMutation::with(['asset', 'pengaju', 'penyetuju'])->findOrFail($id);

        if ($role === 'staff' && $mutation->diajukan_oleh !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }

        return view("{$role}.mutasi.show", compact('mutation'));
    }

    // ========================
    // APPROVE (Admin only)
    // ========================
    public function approve(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $mutation = AssetMutation::findOrFail($id);

        if ($mutation->status !== 'menunggu') {
            return back()->with('error', 'Mutasi ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $mutation->update([
                'status'              => 'disetujui',
                'disetujui_oleh'      => Auth::id(),
                'tanggal_persetujuan' => now()->toDateString(),
            ]);

            // Update unit & lokasi aset
            $mutation->asset->update([
                'unit'   => $mutation->unit_tujuan,
                'lokasi' => $mutation->lokasi_tujuan ?? $mutation->asset->lokasi,
            ]);

            // Notifikasi ke pengaju
            Notification::create([
                'user_id' => $mutation->diajukan_oleh,
                'title'   => 'Mutasi Aset Disetujui',
                'message' => "Pengajuan mutasi {$mutation->nomor_mutasi} untuk aset \"{$mutation->asset->nama}\" telah DISETUJUI. Aset sekarang di unit {$mutation->unit_tujuan}.",
                'type'    => 'success',
                'is_read' => false,
            ]);

            DB::commit();
            return back()->with('success', "Mutasi {$mutation->nomor_mutasi} disetujui. Aset dipindahkan ke unit {$mutation->unit_tujuan}.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal approve mutasi: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyetujui mutasi.');
        }
    }

    // ========================
    // REJECT (Admin only)
    // ========================
    public function reject(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'catatan_penolakan' => 'required|string|min:5',
        ], [
            'catatan_penolakan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $mutation = AssetMutation::findOrFail($id);

        if ($mutation->status !== 'menunggu') {
            return back()->with('error', 'Mutasi ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $mutation->update([
                'status'              => 'ditolak',
                'disetujui_oleh'      => Auth::id(),
                'tanggal_persetujuan' => now()->toDateString(),
                'catatan_penolakan'   => $request->catatan_penolakan,
            ]);

            Notification::create([
                'user_id' => $mutation->diajukan_oleh,
                'title'   => 'Mutasi Aset Ditolak',
                'message' => "Pengajuan mutasi {$mutation->nomor_mutasi} untuk aset \"{$mutation->asset->nama}\" DITOLAK. Alasan: {$request->catatan_penolakan}",
                'type'    => 'warning',
                'is_read' => false,
            ]);

            DB::commit();
            return back()->with('success', "Mutasi {$mutation->nomor_mutasi} telah ditolak.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal reject mutasi: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menolak mutasi.');
        }
    }

    // ========================
    // DESTROY
    // ========================
    public function destroy($id)
    {
        $role     = Auth::user()->role;
        $mutation = AssetMutation::findOrFail($id);

        if ($role === 'staff') {
            if ($mutation->diajukan_oleh !== Auth::id() || $mutation->status !== 'menunggu') {
                abort(403, 'Anda tidak berhak menghapus mutasi ini.');
            }
        }

        if ($mutation->berita_acara) {
            Storage::disk('public')->delete($mutation->berita_acara);
        }

        $mutation->delete();
        return back()->with('success', 'Data mutasi berhasil dihapus.');
    }

    // ========================
    // DOWNLOAD BERITA ACARA
    // ========================
    public function downloadBeritaAcara($id)
    {
        $role     = Auth::user()->role;
        $mutation = AssetMutation::findOrFail($id);

        if (!$mutation->berita_acara || !Storage::disk('public')->exists($mutation->berita_acara)) {
            return back()->with('error', 'File berita acara tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $mutation->berita_acara,
            "BeritaAcara_{$mutation->nomor_mutasi}." . pathinfo($mutation->berita_acara, PATHINFO_EXTENSION)
        );
    }
}
