<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetDestruction;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class AssetDestructionController extends Controller
{
    // ========================
    // INDEX
    // ========================
    public function index(Request $request)
    {
        $role = Auth::user()->role;

        $query = AssetDestruction::with(['asset', 'pengaju', 'penyetuju'])
            ->orderBy('created_at', 'desc');

        if ($role === 'staff') {
            $query->where('diajukan_oleh', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_pemusnahan', 'like', "%{$request->search}%")
                  ->orWhereHas('asset', fn($a) => $a->where('nama', 'like', "%{$request->search}%")
                      ->orWhere('kode_asset', 'like', "%{$request->search}%"));
            });
        }

        $destructions = $query->paginate(15)->withQueryString();

        $statsQuery = AssetDestruction::query();
        if ($role === 'staff') {
            $statsQuery->where('diajukan_oleh', Auth::id());
        }

        $stats = [
            'total'     => (clone $statsQuery)->count(),
            'menunggu'  => (clone $statsQuery)->menunggu()->count(),
            'disetujui' => (clone $statsQuery)->disetujui()->count(),
            'ditolak'   => (clone $statsQuery)->where('status', 'ditolak')->count(),
        ];

        return view("{$role}.pemusnahan.index", compact('destructions', 'stats'));
    }

    // ========================
    // CREATE (Staff)
    // ========================
    public function create()
    {
        $role   = Auth::user()->role;
        $assets = Asset::whereNotIn('status', ['dimusnahkan'])
            ->orderBy('nama')
            ->get(['id', 'nama', 'kode_asset', 'kategori', 'unit', 'lokasi', 'status']);

        return view("{$role}.pemusnahan.create", compact('assets'));
    }

    // ========================
    // STORE (Staff)
    // ========================
    public function store(Request $request)
    {
        $role = Auth::user()->role;

        $request->validate([
            'asset_id'           => 'required|exists:assets,id',
            'kondisi_aset'       => 'nullable|string|max:255',
            'tanggal_usulan'     => 'required|date',
            'alasan_pemusnahan'  => 'required|string|min:10',
        ], [
            'asset_id.required'          => 'Pilih aset yang akan diusulkan untuk dimusnahkan.',
            'tanggal_usulan.required'    => 'Tanggal usulan wajib diisi.',
            'alasan_pemusnahan.required' => 'Alasan pemusnahan wajib diisi.',
            'alasan_pemusnahan.min'      => 'Alasan minimal 10 karakter.',
        ]);

        $asset = Asset::findOrFail($request->asset_id);

        if ($asset->status === 'dimusnahkan') {
            return back()->withErrors(['asset_id' => 'Aset ini sudah dimusnahkan sebelumnya.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $destruction = AssetDestruction::create([
                'nomor_pemusnahan'  => AssetDestruction::generateNomor(),
                'asset_id'          => $asset->id,
                'alasan_pemusnahan' => $request->alasan_pemusnahan,
                'kondisi_aset'      => $request->kondisi_aset,
                'tanggal_usulan'    => $request->tanggal_usulan,
                'diajukan_oleh'     => Auth::id(),
                'status'            => 'menunggu',
            ]);

            // Notifikasi ke admin
            if ($role !== 'admin') {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title'   => 'Usulan Pemusnahan Aset Baru',
                        'message' => "Pemusnahan aset \"{$asset->nama}\" ({$destruction->nomor_pemusnahan}) diusulkan oleh " . Auth::user()->name . ".",
                        'type'    => 'info',
                        'is_read' => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route("{$role}.pemusnahan.index")
                ->with('success', "Usulan pemusnahan {$destruction->nomor_pemusnahan} berhasil dibuat dan menunggu persetujuan admin.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan usulan pemusnahan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan, silakan coba lagi.')->withInput();
        }
    }

    // ========================
    // SHOW
    // ========================
    public function show($id)
    {
        $role        = Auth::user()->role;
        $destruction = AssetDestruction::with(['asset', 'pengaju', 'penyetuju'])->findOrFail($id);

        if ($role === 'staff' && $destruction->diajukan_oleh !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }

        return view("{$role}.pemusnahan.show", compact('destruction'));
    }

    // ========================
    // APPROVE (Admin only) — generate & simpan Berita Acara PDF
    // ========================
    public function approve(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $destruction = AssetDestruction::with(['asset', 'pengaju'])->findOrFail($id);

        if ($destruction->status !== 'menunggu') {
            return back()->with('error', 'Usulan pemusnahan ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $destruction->update([
                'status'              => 'disetujui',
                'disetujui_oleh'      => Auth::id(),
                'tanggal_persetujuan' => now()->toDateString(),
                'tanggal_pemusnahan'  => now()->toDateString(),
            ]);

            // Refresh relasi penyetuju supaya terbaca di PDF
            $destruction->refresh()->load(['asset', 'pengaju', 'penyetuju']);

            // Generate Berita Acara PDF
            $pdf = Pdf::loadView('reports.pdf.berita-acara-pemusnahan', ['destruction' => $destruction]);
            $pdf->setPaper('a4', 'portrait');

            $filename = 'berita-acara-pemusnahan/BA_' . str_replace('/', '-', $destruction->nomor_pemusnahan) . '.pdf';
            Storage::disk('public')->put($filename, $pdf->output());

            $destruction->update(['berita_acara' => $filename]);

            // Tandai aset sebagai dimusnahkan
            $destruction->asset->update(['status' => 'dimusnahkan']);

            // Notifikasi ke pengaju
            Notification::create([
                'user_id' => $destruction->diajukan_oleh,
                'title'   => 'Usulan Pemusnahan Aset Disetujui',
                'message' => "Usulan pemusnahan {$destruction->nomor_pemusnahan} untuk aset \"{$destruction->asset->nama}\" telah DISETUJUI. Berita Acara sudah dapat diunduh.",
                'type'    => 'success',
                'is_read' => false,
            ]);

            DB::commit();
            return back()->with('success', "Pemusnahan {$destruction->nomor_pemusnahan} disetujui. Berita Acara telah dibuat.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal approve pemusnahan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyetujui pemusnahan: ' . $e->getMessage());
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

        $destruction = AssetDestruction::findOrFail($id);

        if ($destruction->status !== 'menunggu') {
            return back()->with('error', 'Usulan pemusnahan ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $destruction->update([
                'status'              => 'ditolak',
                'disetujui_oleh'      => Auth::id(),
                'tanggal_persetujuan' => now()->toDateString(),
                'catatan_penolakan'   => $request->catatan_penolakan,
            ]);

            Notification::create([
                'user_id' => $destruction->diajukan_oleh,
                'title'   => 'Usulan Pemusnahan Aset Ditolak',
                'message' => "Usulan pemusnahan {$destruction->nomor_pemusnahan} untuk aset \"{$destruction->asset->nama}\" DITOLAK. Alasan: {$request->catatan_penolakan}",
                'type'    => 'warning',
                'is_read' => false,
            ]);

            DB::commit();
            return back()->with('success', "Usulan pemusnahan {$destruction->nomor_pemusnahan} telah ditolak.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal reject pemusnahan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menolak usulan.');
        }
    }

    // ========================
    // DESTROY (batalkan usulan yang masih menunggu)
    // ========================
    public function destroy($id)
    {
        $role        = Auth::user()->role;
        $destruction = AssetDestruction::findOrFail($id);

        if ($role === 'staff') {
            if ($destruction->diajukan_oleh !== Auth::id() || $destruction->status !== 'menunggu') {
                abort(403, 'Anda tidak berhak menghapus usulan ini.');
            }
        }

        if ($destruction->status === 'menunggu') {
            $destruction->delete();
            return back()->with('success', 'Usulan pemusnahan berhasil dibatalkan.');
        }

        return back()->with('error', 'Usulan yang sudah diproses tidak dapat dihapus.');
    }

    // ========================
    // DOWNLOAD BERITA ACARA
    // ========================
    public function downloadBeritaAcara($id)
    {
        $role        = Auth::user()->role;
        $destruction = AssetDestruction::findOrFail($id);

        if ($role === 'staff' && $destruction->diajukan_oleh !== Auth::id()) {
            abort(403);
        }

        if (!$destruction->berita_acara || !Storage::disk('public')->exists($destruction->berita_acara)) {
            return back()->with('error', 'Berita Acara belum tersedia (usulan belum disetujui).');
        }

        $safeNomor = str_replace('/', '-', $destruction->nomor_pemusnahan);

        return Storage::disk('public')->download(
            $destruction->berita_acara,
            "BeritaAcara_Pemusnahan_{$safeNomor}.pdf"
        );
    }
}
