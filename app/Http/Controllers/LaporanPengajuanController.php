<?php

namespace App\Http\Controllers;

use App\Models\LaporanPengajuan;
use App\Models\Notification;
use App\Models\User;
use App\Models\DocumentSignature;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPengajuanController extends Controller
{
    protected WhatsAppService $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    // ══════════════════════════════════════════════════════
    // ADMIN & STAFF — Daftar pengajuan milik sendiri
    // ══════════════════════════════════════════════════════

    public function index()
    {
        $role = Auth::user()->role;
        $pengajuan = LaporanPengajuan::with(['pengaju', 'validator'])
            ->where('diajukan_oleh', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view("{$role}.laporan.pengajuan.index", compact('pengajuan'));
    }

    // ══════════════════════════════════════════════════════
    // ADMIN & STAFF — Ajukan laporan ke pimpinan
    // ══════════════════════════════════════════════════════

    public function ajukan(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|in:' . implode(',', LaporanPengajuan::jenisYangBisaDiajukan()),
        ]);

        $role  = Auth::user()->role;
        $jenis = $request->jenis_laporan;
        $judul = LaporanPengajuan::labelJenis($jenis);

        // Cek pengajuan yang masih menunggu untuk jenis yang sama
        $existing = LaporanPengajuan::where('diajukan_oleh', Auth::id())
            ->where('jenis_laporan', $jenis)
            ->where('status', 'menunggu')
            ->first();

        if ($existing) {
            return back()->with('warning', "Sudah ada pengajuan {$judul} yang sedang menunggu validasi pimpinan.");
        }

        // Simpan parameter filter laporan
        $parameter = $request->except(['_token', 'jenis_laporan']);

        LaporanPengajuan::create([
            'jenis_laporan' => $jenis,
            'parameter'     => $parameter ?: [],
            'judul'         => $judul,
            'diajukan_oleh' => Auth::id(),
            'diajukan_at'   => now(),
            'status'        => 'menunggu',
        ]);

        // Notifikasi ke semua pimpinan
        $pimpinanList = User::where('role', 'pimpinan')->get();

        $pimpinanList->each(function ($pimpinan) use ($judul) {
            Notification::create([
                'user_id' => $pimpinan->id,
                'title'   => '📋 Pengajuan Laporan Baru',
                'message' => Auth::user()->name . " mengajukan {$judul} untuk divalidasi TTE.",
                'type'    => 'info',
                'is_read' => false,
            ]);
        });

        // ✅ TAMBAHAN BARU (Poin 5 revisi) — Notifikasi WhatsApp ke pimpinan
        $pesanWa = "📋 *Pengajuan Laporan Baru*\n\n"
                 . "{$judul}\n"
                 . "Diajukan oleh: " . Auth::user()->name . "\n\n"
                 . "Segera cek & validasi TTE di aplikasi GANDARIA:\n"
                 . route('pimpinan.laporan.validasi.index');

        $this->whatsapp->sendToMany($pimpinanList->pluck('phone')->all(), $pesanWa);

        return back()->with('success', 'Surat berhasil diajukan.');
    }

    // ══════════════════════════════════════════════════════
    // ADMIN & STAFF — Ajukan ulang setelah ditolak
    // ══════════════════════════════════════════════════════

    public function ajukanUlang($id)
    {
        $pengajuan = LaporanPengajuan::where('diajukan_oleh', Auth::id())
            ->where('status', 'ditolak')
            ->findOrFail($id);

        $pengajuan->update([
            'status'          => 'menunggu',
            'catatan'         => null,
            'divalidasi_oleh' => null,
            'divalidasi_at'   => null,
            'tte_token'       => null,
            'diajukan_at'     => now(),
        ]);

        $pimpinanList = User::where('role', 'pimpinan')->get();

        $pimpinanList->each(function ($pimpinan) use ($pengajuan) {
            Notification::create([
                'user_id' => $pimpinan->id,
                'title'   => '📋 Pengajuan Laporan Ulang',
                'message' => Auth::user()->name . " mengajukan ulang {$pengajuan->judul}.",
                'type'    => 'info',
                'is_read' => false,
            ]);
        });

        // ✅ TAMBAHAN BARU (Poin 5 revisi) — Notifikasi WhatsApp ke pimpinan
        $pesanWa = "📋 *Pengajuan Laporan Ulang*\n\n"
                 . "{$pengajuan->judul}\n"
                 . "Diajukan ulang oleh: " . Auth::user()->name . "\n\n"
                 . "Segera cek & validasi TTE di aplikasi GANDARIA:\n"
                 . route('pimpinan.laporan.validasi.index');

        $this->whatsapp->sendToMany($pimpinanList->pluck('phone')->all(), $pesanWa);

        return back()->with('success', 'Pengajuan laporan berhasil dikirim ulang ke pimpinan.');
    }

    // ══════════════════════════════════════════════════════
    // ADMIN & STAFF — Download PDF dengan TTE
    // ══════════════════════════════════════════════════════

    public function download($id)
    {
        $pengajuan = LaporanPengajuan::with('pengaju')->findOrFail($id);

        if (!$pengajuan->canDownload(Auth::id())) {
            abort(403, 'Laporan belum divalidasi atau Anda tidak berhak mengunduh.');
        }

        return $this->generatePdfWithTTE($pengajuan);
    }

    // ══════════════════════════════════════════════════════
    // PIMPINAN — Daftar semua pengajuan untuk divalidasi
    // ══════════════════════════════════════════════════════

    public function daftarValidasi()
    {
        $pengajuan = LaporanPengajuan::with(['pengaju', 'validator'])
            ->orderByRaw("FIELD(status, 'menunggu', 'disetujui', 'ditolak')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'menunggu'  => LaporanPengajuan::where('status', 'menunggu')->count(),
            'disetujui' => LaporanPengajuan::where('status', 'disetujui')->count(),
            'ditolak'   => LaporanPengajuan::where('status', 'ditolak')->count(),
        ];

        return view('pimpinan.laporan.validasi.index', compact('pengajuan', 'stats'));
    }

    // ══════════════════════════════════════════════════════
    // PIMPINAN — Preview laporan sebelum divalidasi
    // ══════════════════════════════════════════════════════

    public function previewValidasi($id)
    {
        $pengajuan = LaporanPengajuan::findOrFail($id);
        $reportCtrl = new ReportController();
        $req = new \Illuminate\Http\Request();
        $req->merge(array_merge($pengajuan->parameter ?? [], ['type' => $pengajuan->jenis_laporan]));

        $data = $reportCtrl->getExportData($pengajuan->jenis_laporan, $req);
        $data['signature']   = null;
        $data['qrSvg']       = null;
        $data['validasiUrl'] = null;

        $pdf = Pdf::loadView("reports.pdf.{$pengajuan->jenis_laporan}", $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream("preview_{$pengajuan->jenis_laporan}.pdf");
    }

    // ══════════════════════════════════════════════════════
    // PIMPINAN — Setujui & bubuhkan TTE
    // ══════════════════════════════════════════════════════

    public function setujui($id)
    {
        $pengajuan = LaporanPengajuan::findOrFail($id);

        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        // Buat DocumentSignature baru
        $signature = DocumentSignature::generateFor(
            documentType:  $pengajuan->jenis_laporan,
            documentTitle: $pengajuan->judul,
            signedBy:      Auth::user()->name,
            signedByTitle: 'Aris Saputera, S.STP.,MSi.',
            metadata:      [
                'pengajuan_id'  => $pengajuan->id,
                'diajukan_oleh' => $pengajuan->pengaju->name ?? '-',
                'validated_by'  => Auth::user()->name,
            ]
        );

        $pengajuan->update([
            'status'          => 'disetujui',
            'divalidasi_oleh' => Auth::id(),
            'divalidasi_at'   => now(),
            'tte_token'       => $signature->token,
        ]);

        // Notifikasi ke pengaju
        Notification::create([
            'user_id' => $pengajuan->diajukan_oleh,
            'title'   => 'Laporan Disetujui & TTE',
            'message' => "{$pengajuan->judul} telah disetujui dan ditandatangani oleh " . Auth::user()->name . ". Silakan download PDF dengan TTE.",
            'type'    => 'success',
            'is_read' => false,
        ]);

        return back()->with('success', 'Surat berhasil disetujui.');
    }

    // ══════════════════════════════════════════════════════
    // PIMPINAN — Tolak pengajuan
    // ══════════════════════════════════════════════════════

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|min:5',
        ], ['catatan.required' => 'Alasan penolakan wajib diisi.']);

        $pengajuan = LaporanPengajuan::findOrFail($id);

        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $pengajuan->update([
            'status'          => 'ditolak',
            'divalidasi_oleh' => Auth::id(),
            'divalidasi_at'   => now(),
            'catatan'         => $request->catatan,
        ]);

        Notification::create([
            'user_id' => $pengajuan->diajukan_oleh,
            'title'   => '❌ Pengajuan Laporan Ditolak',
            'message' => "{$pengajuan->judul} ditolak. Alasan: {$request->catatan}. Anda dapat mengajukan ulang.",
            'type'    => 'warning',
            'is_read' => false,
        ]);

        return back()->with('success', "Pengajuan {$pengajuan->judul} berhasil ditolak.");
    }

    // ══════════════════════════════════════════════════════
    // PIMPINAN — Download PDF dengan TTE
    // ══════════════════════════════════════════════════════

    public function downloadPimpinan($id)
    {
        $pengajuan = LaporanPengajuan::findOrFail($id);

        if (!$pengajuan->isApproved()) {
            abort(403, 'Laporan belum divalidasi.');
        }

        return $this->generatePdfWithTTE($pengajuan);
    }

    // ══════════════════════════════════════════════════════
    // PRIVATE — Generate PDF dengan TTE
    // ══════════════════════════════════════════════════════

    private function generatePdfWithTTE(LaporanPengajuan $pengajuan)
    {
        $signature = DocumentSignature::where('token', $pengajuan->tte_token)->first();
        $validasiUrl = $signature ? url('/validasi/' . $signature->token) : null;

        $reportCtrl = new ReportController();
        $req = new \Illuminate\Http\Request();
        $req->merge(array_merge($pengajuan->parameter ?? [], ['type' => $pengajuan->jenis_laporan]));

        $data = $reportCtrl->getExportData($pengajuan->jenis_laporan, $req);
        $data['signature']   = $signature;
        $data['qrSvg']       = $validasiUrl ? $reportCtrl->generateQrDataUri($validasiUrl) : null;
        $data['validasiUrl'] = $validasiUrl;

        $pdf = Pdf::loadView("reports.pdf.{$pengajuan->jenis_laporan}", $data)
            ->setPaper('a4', 'portrait');

        $filename = "laporan_{$pengajuan->jenis_laporan}_TTE_" . now()->format('Ymd') . ".pdf";

        return $pdf->download($filename);
    }
}
