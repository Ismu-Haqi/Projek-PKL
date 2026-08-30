<?php

/**
 * Controller Surat Keluar — lengkap dengan alur Tanda Tangan Elektronik (TTE):
 * Staf membuat surat (draft) → ajukan TTE ke pimpinan (menunggu_tte) →
 * pimpinan menyetujui (ditandatangani, token+QR dibuat) atau menolak (ditolak).
 */

namespace App\Http\Controllers;

use App\Models\OutgoingLetter;
use App\Models\DocumentSignature;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\WhatsAppService;

class OutgoingLetterController extends Controller
{
    protected WhatsAppService $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    // ========================
    // INDEX
    // ========================
    public function index(Request $request)
    {
        $role = Auth::user()->role;

        $query = OutgoingLetter::with('pembuat')->orderBy('tanggal_surat', 'desc');

        if ($role === 'staff') {
            $query->where('dibuat_oleh', Auth::id());
        } elseif ($role === 'pimpinan') {
            // Pimpinan fokus ke surat yang butuh/sudah tindakannya, tapi tetap bisa lihat semua
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_agenda', 'like', "%{$request->search}%")
                  ->orWhere('perihal', 'like', "%{$request->search}%")
                  ->orWhere('tujuan', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $letters = $query->paginate(15)->withQueryString();

        $statsBase = OutgoingLetter::query();
        if ($role === 'staff') {
            $statsBase->where('dibuat_oleh', Auth::id());
        }

        $stats = [
            'total'        => (clone $statsBase)->count(),
            'bulan_ini'    => (clone $statsBase)->whereMonth('tanggal_surat', now()->month)
                                        ->whereYear('tanggal_surat', now()->year)->count(),
            'menunggu_tte' => (clone $statsBase)->where('status', 'menunggu_tte')->count(),
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
    // AJUKAN TTE (Staf/Admin → Pimpinan)
    // ========================
    public function ajukanTte($id)
    {
        $letter = OutgoingLetter::findOrFail($id);

        if (Auth::user()->role === 'staff' && $letter->dibuat_oleh !== Auth::id()) {
            abort(403);
        }

        if (!in_array($letter->status, ['draft', 'ditolak'])) {
            return back()->with('error', 'Surat ini sudah diajukan/diproses sebelumnya.');
        }

        $letter->update([
            'status'          => 'menunggu_tte',
            'diajukan_tte_at' => now(),
            'catatan_penolakan' => null,
        ]);

        $pimpinanList = User::where('role', 'pimpinan')->get();
        foreach ($pimpinanList as $pimpinan) {
            Notification::create([
                'user_id' => $pimpinan->id,
                'title'   => 'Surat Keluar Menunggu TTE',
                'message' => "Surat \"{$letter->perihal}\" ({$letter->nomor_agenda}) diajukan oleh " . Auth::user()->name . " untuk ditandatangani.",
                'type'    => 'info',
                'is_read' => false,
            ]);
        }

        // ✅ TAMBAHAN BARU (Poin 5 revisi) — Notifikasi WhatsApp ke pimpinan
        $pesanWa = "✍️ *Surat Keluar Menunggu TTE*\n\n"
                 . "Perihal: {$letter->perihal}\n"
                 . "No. Agenda: {$letter->nomor_agenda}\n"
                 . "Diajukan oleh: " . Auth::user()->name . "\n\n"
                 . "Segera cek & tandatangani di aplikasi GANDARIA:\n"
                 . route('pimpinan.surat-keluar.show', $letter->id);

        $this->whatsapp->sendToMany($pimpinanList->pluck('phone')->all(), $pesanWa);

        $role = Auth::user()->role;
        return redirect()->route("{$role}.surat-keluar.show", $letter->id)
            ->with('success', 'Surat berhasil diajukan untuk TTE ke pimpinan.');
    }

    // ========================
    // SETUJUI TTE (Pimpinan)
    // ========================
    public function setujuiTte($id)
    {
        if (Auth::user()->role !== 'pimpinan') abort(403);

        $letter = OutgoingLetter::findOrFail($id);

        if ($letter->status !== 'menunggu_tte') {
            return back()->with('error', 'Surat ini sudah diproses sebelumnya.');
        }

        $signature = DocumentSignature::generateFor(
            documentType:  'surat_keluar',
            documentTitle: $letter->perihal,
            signedBy:      'Aris Saputera, S.STP.,MSi.',
            signedByTitle: 'Kepala Dinas',
            metadata:      [
                'outgoing_letter_id' => $letter->id,
                'nomor_agenda'       => $letter->nomor_agenda,
                'dibuat_oleh'        => $letter->pembuat->name ?? '-',
                'validated_by'       => Auth::user()->name,
            ]
        );

        $letter->update([
            'status'          => 'ditandatangani',
            'tte_token'       => $signature->token,
            'divalidasi_oleh' => Auth::id(),
            'divalidasi_at'   => now(),
        ]);

        Notification::create([
            'user_id' => $letter->dibuat_oleh,
            'title'   => 'Surat Keluar Disetujui & TTE',
            'message' => "Surat \"{$letter->perihal}\" ({$letter->nomor_agenda}) telah ditandatangani oleh " . Auth::user()->name . ". Silakan unduh PDF dengan TTE.",
            'type'    => 'success',
            'is_read' => false,
        ]);

        return back()->with('success', "Surat {$letter->nomor_agenda} berhasil disetujui dan TTE dibubuhkan.");
    }

    // ========================
    // TOLAK TTE (Pimpinan)
    // ========================
    public function tolakTte(Request $request, $id)
    {
        if (Auth::user()->role !== 'pimpinan') abort(403);

        $request->validate([
            'catatan_penolakan' => 'required|string|min:5',
        ], ['catatan_penolakan.required' => 'Alasan penolakan wajib diisi.']);

        $letter = OutgoingLetter::findOrFail($id);

        if ($letter->status !== 'menunggu_tte') {
            return back()->with('error', 'Surat ini sudah diproses sebelumnya.');
        }

        $letter->update([
            'status'             => 'ditolak',
            'divalidasi_oleh'    => Auth::id(),
            'divalidasi_at'      => now(),
            'catatan_penolakan'  => $request->catatan_penolakan,
        ]);

        Notification::create([
            'user_id' => $letter->dibuat_oleh,
            'title'   => 'Surat Keluar Ditolak',
            'message' => "Surat \"{$letter->perihal}\" ({$letter->nomor_agenda}) ditolak oleh " . Auth::user()->name . ". Alasan: {$request->catatan_penolakan}",
            'type'    => 'warning',
            'is_read' => false,
        ]);

        return back()->with('success', "Surat {$letter->nomor_agenda} telah ditolak.");
    }

    // ========================
    // DOWNLOAD PDF SURAT (dengan QR TTE jika sudah ditandatangani)
    // ========================
    public function downloadPdf($id)
    {
        $role   = Auth::user()->role;
        $letter = OutgoingLetter::with(['pembuat', 'penandatangan'])->findOrFail($id);

        if ($role === 'staff' && $letter->dibuat_oleh !== Auth::id()) {
            abort(403);
        }

        $data = ['letter' => $letter, 'qrSvg' => null, 'validasiUrl' => null, 'signature' => null];

        if ($letter->status === 'ditandatangani' && $letter->tte_token) {
            $signature = DocumentSignature::where('token', $letter->tte_token)->first();
            if ($signature) {
                $validasiUrl = url('/validasi/' . $letter->tte_token);
                $data['signature']   = $signature;
                $data['validasiUrl'] = $validasiUrl;
                $data['qrSvg']       = $this->generateQrDataUri($validasiUrl);
            }
        }

        $pdf = Pdf::loadView('reports.pdf.surat-keluar', $data);
        $pdf->setPaper('a4', 'portrait');

        $safeNomor = str_replace('/', '-', $letter->nomor_agenda);
        return $pdf->download("SuratKeluar_{$safeNomor}.pdf");
    }

    /**
     * Generate QR Code sebagai data URI (path file SVG untuk DomPDF).
     * Sama seperti method di ReportController.
     */
    private function generateQrDataUri(string $url): string
    {
        try {
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(150)
                ->margin(1)
                ->errorCorrection('L')
                ->generate($url);

            $filename = 'tte_' . md5($url) . '.svg';
            $path     = 'tte/' . $filename;
            Storage::disk('public')->put($path, $qrCode);

            return Storage::disk('public')->path($path);
        } catch (\Exception $e) {
            Log::error('QR TTE Error (Surat Keluar): ' . $e->getMessage());
            return '';
        }
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

        if (!in_array($letter->status, ['draft', 'ditolak'])) {
            return back()->with('error', 'Surat yang sudah diajukan/ditandatangani tidak dapat dihapus.');
        }

        if ($letter->file_path) {
            Storage::disk('public')->delete($letter->file_path);
        }

        $letter->delete();
        return back()->with('success', 'Surat keluar berhasil dihapus.');
    }
}
