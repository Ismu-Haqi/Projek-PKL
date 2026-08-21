<?php

namespace App\Http\Controllers;

use App\Models\IncomingLetter;
use App\Models\Disposition;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class IncomingLetterController extends Controller
{
    // ─── Index ────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $role = Auth::user()->role;

        $query = IncomingLetter::with(['uploader', 'disposition'])
            ->orderBy('tanggal_diterima', 'desc');

        // Staff hanya lihat surat miliknya sendiri
        if ($role === 'staff') {
            $query->where('uploaded_by', Auth::id());
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('sifat')) {
            $query->bySifat($request->sifat);
        }

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_diterima', $request->bulan)
                  ->whereYear('tanggal_diterima', $request->tahun);
        }

        $letters = $query->paginate(15)->withQueryString();

        // Statistik: staff hanya lihat statistik surat miliknya sendiri,
        // admin & pimpinan tetap lihat statistik global (semua surat)
        $statsQuery = IncomingLetter::query();
        if ($role === 'staff') {
            $statsQuery->where('uploaded_by', Auth::id());
        }

        $stats = [
            'total'           => (clone $statsQuery)->count(),
            'belum_disposisi' => (clone $statsQuery)->byStatus('belum_disposisi')->count(),
            'sudah_disposisi' => (clone $statsQuery)->byStatus('sudah_disposisi')->count(),
            'selesai'         => (clone $statsQuery)->byStatus('selesai')->count(),
            'bulan_ini'       => (clone $statsQuery)->whereMonth('tanggal_diterima', now()->month)
                                               ->whereYear('tanggal_diterima', now()->year)
                                               ->count(),
        ];

        $viewPrefix = $role === 'staff' ? 'staff' : ($role === 'pimpinan' ? 'pimpinan' : 'admin');

        return view("{$viewPrefix}.surat-masuk.index", compact('letters', 'stats'));
    }

    // ─── Create ───────────────────────────────────────────────────────────────

    public function create()
    {
        $nomorAgenda = IncomingLetter::generateNomorAgenda();
        $role = Auth::user()->role;
        $viewPrefix = $role === 'staff' ? 'staff' : 'admin';

        return view("{$viewPrefix}.surat-masuk.create", compact('nomorAgenda'));
    }

    // ─── Store ────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat'     => 'required|string|max:100',
            'tanggal_surat'   => 'required|date',
            'tanggal_diterima'=> 'required|date',
            'pengirim'        => 'required|string|max:255',
            'perihal'         => 'required|string|max:500',
            'sifat'           => 'required|in:biasa,segera,sangat_segera,rahasia',
            'kategori'        => 'nullable|string|max:100',
            'unit_tujuan'     => 'nullable|string|max:100',
            'keterangan'      => 'nullable|string',
            'file_surat'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'nomor_surat.required'      => 'Nomor surat harus diisi',
            'tanggal_surat.required'    => 'Tanggal surat harus diisi',
            'tanggal_diterima.required' => 'Tanggal diterima harus diisi',
            'pengirim.required'         => 'Pengirim harus diisi',
            'perihal.required'          => 'Perihal harus diisi',
            'sifat.required'            => 'Sifat surat harus dipilih',
            'file_surat.mimes'          => 'File harus berformat PDF, JPG, atau PNG',
            'file_surat.max'            => 'Ukuran file maksimal 10MB',
        ]);

        try {
            $nomorAgenda = IncomingLetter::generateNomorAgenda();
            $data = [
                'nomor_agenda'     => $nomorAgenda,
                'nomor_surat'      => $request->nomor_surat ?: $nomorAgenda, // fallback ke nomor agenda
                'tanggal_surat'    => $request->tanggal_surat,
                'tanggal_diterima' => $request->tanggal_diterima,
                'pengirim'         => $request->pengirim,
                'perihal'          => $request->perihal,
                'sifat'            => $request->sifat,
                'kategori'         => $request->kategori,
                'unit_tujuan'      => $request->unit_tujuan,
                'keterangan'       => $request->keterangan,
                'status'           => 'belum_disposisi',
                'uploaded_by'      => Auth::id(),
            ];

            // Upload file scan surat
            if ($request->hasFile('file_surat')) {
                $file     = $request->file('file_surat');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path     = $file->storeAs('surat-masuk', $filename, 'public');

                $data['file_path'] = $path;
                $data['file_name'] = $file->getClientOriginalName();
                $data['file_size'] = $file->getSize();
                $data['file_type'] = strtoupper($file->getClientOriginalExtension());
            }

            IncomingLetter::create($data);

            return redirect()
                ->route(Auth::user()->role . '.surat-masuk.index')
                ->with('success', 'Surat masuk berhasil diinput!');

        } catch (\Exception $e) {
            Log::error('IncomingLetter store error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan surat: ' . $e->getMessage());
        }
    }

    // ─── Show ─────────────────────────────────────────────────────────────────

    public function show(int $id)
    {
        $letter = IncomingLetter::with(['uploader', 'disposition.toUser'])->findOrFail($id);
        $role   = Auth::user()->role;
        $viewPrefix = $role === 'staff' ? 'staff' : ($role === 'pimpinan' ? 'pimpinan' : 'admin');

        return view("{$viewPrefix}.surat-masuk.show", compact('letter'));
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────

    public function edit(int $id)
    {
        $letter = IncomingLetter::findOrFail($id);

        // Surat yang sudah disposisi tidak bisa diedit
        if ($letter->status !== 'belum_disposisi') {
            return back()->with('error', 'Surat yang sudah didisposisi tidak dapat diedit.');
        }

        $role       = Auth::user()->role;
        $viewPrefix = $role === 'staff' ? 'staff' : 'admin';

        return view("{$viewPrefix}.surat-masuk.edit", compact('letter'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update(Request $request, int $id)
    {
        $letter = IncomingLetter::findOrFail($id);

        if ($letter->status !== 'belum_disposisi') {
            return back()->with('error', 'Surat yang sudah didisposisi tidak dapat diedit.');
        }

        $request->validate([
            'nomor_surat'     => 'required|string|max:100',
            'tanggal_surat'   => 'required|date',
            'tanggal_diterima'=> 'required|date',
            'pengirim'        => 'required|string|max:255',
            'perihal'         => 'required|string|max:500',
            'sifat'           => 'required|in:biasa,segera,sangat_segera,rahasia',
            'kategori'        => 'nullable|string|max:100',
            'unit_tujuan'     => 'nullable|string|max:100',
            'keterangan'      => 'nullable|string',
            'file_surat'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            $data = $request->only([
                'nomor_surat', 'tanggal_surat', 'tanggal_diterima',
                'pengirim', 'perihal', 'sifat', 'kategori', 'unit_tujuan', 'keterangan',
            ]);

            if ($request->hasFile('file_surat')) {
                // Hapus file lama
                if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
                    Storage::disk('public')->delete($letter->file_path);
                }

                $file     = $request->file('file_surat');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path     = $file->storeAs('surat-masuk', $filename, 'public');

                $data['file_path'] = $path;
                $data['file_name'] = $file->getClientOriginalName();
                $data['file_size'] = $file->getSize();
                $data['file_type'] = strtoupper($file->getClientOriginalExtension());
            }

            $letter->update($data);

            return redirect()
                ->route(Auth::user()->role . '.surat-masuk.show', $id)
                ->with('success', 'Surat masuk berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('IncomingLetter update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    // ─── Destroy ─────────────────────────────────────────────────────────────

    public function destroy(int $id)
    {
        $letter = IncomingLetter::findOrFail($id);

        if ($letter->status === 'sudah_disposisi') {
            return back()->with('error', 'Surat yang sudah disposisi tidak dapat dihapus.');
        }

        $letter->delete(); // Boot method otomatis hapus file

        return redirect()
            ->route(Auth::user()->role . '.surat-masuk.index')
            ->with('success', 'Surat masuk berhasil dihapus.');
    }

    // ─── Preview file ─────────────────────────────────────────────────────────

    public function preview(int $id)
    {
        $letter = IncomingLetter::findOrFail($id);

        if (!$letter->file_path || !Storage::disk('public')->exists($letter->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file(Storage::disk('public')->path($letter->file_path));
    }

    // ─── Download file ────────────────────────────────────────────────────────

    public function download(int $id)
    {
        $letter = IncomingLetter::findOrFail($id);

        if (!$letter->file_path || !Storage::disk('public')->exists($letter->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $letter->file_path,
            $letter->file_name ?? 'surat-masuk.pdf'
        );
    }

    // ─── Buat disposisi dari surat masuk ─────────────────────────────────────

    public function buatDisposisi(int $id)
    {
        $letter = IncomingLetter::findOrFail($id);

        if ($letter->status !== 'belum_disposisi') {
            return back()->with('error', 'Surat ini sudah pernah didisposisi.');
        }

        $role = Auth::user()->role;

        // Redirect ke halaman buat disposisi dengan prefill data surat
        return redirect()
            ->route("{$role}.disposisi.create", [
                'from_letter'  => $letter->id,
                'perihal'      => $letter->perihal,
                'nomor_surat'  => $letter->nomor_surat,
                'pengirim'     => $letter->pengirim,
            ]);
    }

    // ─── Tandai selesai ───────────────────────────────────────────────────────

    public function tandaiSelesai(int $id)
    {
        $letter = IncomingLetter::findOrFail($id);
        $letter->update(['status' => 'selesai']);

        return back()->with('success', 'Surat ditandai selesai.');
    }
}