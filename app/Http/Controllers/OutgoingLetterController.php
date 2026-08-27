<?php

/**
 * CATATAN: Controller ini adalah versi DASAR (CRUD sederhana) sebagai fondasi
 * data Surat Keluar, dibangun untuk mendukung Laporan Rekap Agenda Surat (poin 3).
 * Alur lengkap dengan Tanda Tangan Elektronik (TTE) — termasuk status
 * 'menunggu_tte' & 'ditandatangani', validasi pimpinan, dan token QR —
 * akan ditambahkan menyusul saat mengerjakan poin 1 (Form Surat Keluar dengan TTE).
 */

namespace App\Http\Controllers;

use App\Models\OutgoingLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OutgoingLetterController extends Controller
{
    // ========================
    // INDEX
    // ========================
    public function index(Request $request)
    {
        $role = Auth::user()->role;

        $query = OutgoingLetter::with('pembuat')->orderBy('tanggal_surat', 'desc');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_agenda', 'like', "%{$request->search}%")
                  ->orWhere('perihal', 'like', "%{$request->search}%")
                  ->orWhere('tujuan', 'like', "%{$request->search}%");
            });
        }

        $letters = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => OutgoingLetter::count(),
            'bulan_ini' => OutgoingLetter::whereMonth('tanggal_surat', now()->month)
                                        ->whereYear('tanggal_surat', now()->year)->count(),
        ];

        return view("{$role}.surat-keluar.index", compact('letters', 'stats'));
    }

    // ========================
    // CREATE
    // ========================
    public function create()
    {
        $role = Auth::user()->role;
        return view("{$role}.surat-keluar.create");
    }

    // ========================
    // STORE
    // ========================
    public function store(Request $request)
    {
        $role = Auth::user()->role;

        $request->validate([
            'tanggal_surat' => 'required|date',
            'tujuan'        => 'required|string|max:255',
            'perihal'       => 'required|string|max:255',
            'sifat'         => 'required|in:biasa,penting,segera,rahasia',
            'nomor_surat'   => 'nullable|string|max:100',
            'keterangan'    => 'nullable|string',
            'file'          => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = $request->only(['nomor_surat', 'tanggal_surat', 'tujuan', 'perihal', 'sifat', 'keterangan']);
        $data['nomor_agenda'] = OutgoingLetter::generateNomorAgenda();
        $data['dibuat_oleh']  = Auth::id();
        $data['status']       = 'draft';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('surat-keluar', 'public');
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = round($file->getSize() / 1024, 1) . ' KB';
        }

        OutgoingLetter::create($data);

        return redirect()->route("{$role}.surat-keluar.index")
            ->with('success', 'Surat keluar berhasil dicatat dengan nomor agenda ' . $data['nomor_agenda'] . '.');
    }

    // ========================
    // SHOW
    // ========================
    public function show($id)
    {
        $role   = Auth::user()->role;
        $letter = OutgoingLetter::with('pembuat')->findOrFail($id);
        return view("{$role}.surat-keluar.show", compact('letter'));
    }

    // ========================
    // DOWNLOAD LAMPIRAN
    // ========================
    public function download($id)
    {
        $letter = OutgoingLetter::findOrFail($id);
        if (!$letter->file_path || !Storage::disk('public')->exists($letter->file_path)) {
            return back()->with('error', 'File lampiran tidak ditemukan.');
        }
        return Storage::disk('public')->download($letter->file_path, $letter->file_name);
    }

    // ========================
    // DESTROY
    // ========================
    public function destroy($id)
    {
        $letter = OutgoingLetter::findOrFail($id);

        if (Auth::user()->role === 'staff' && $letter->dibuat_oleh !== Auth::id()) {
            abort(403);
        }

        if ($letter->file_path) {
            Storage::disk('public')->delete($letter->file_path);
        }

        $letter->delete();
        return back()->with('success', 'Surat keluar berhasil dihapus.');
    }
}
