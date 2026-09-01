<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Asset;
use App\Models\Disposition;
use App\Models\User;
use App\Models\AssetBorrow;
use App\Models\IncomingLetter;
use App\Models\DocumentSignature;
use App\Models\AssetDestruction;
use App\Models\OutgoingLetter;
use App\Models\LaporanPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display main report dashboard
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $user = Auth::user();

        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }

        // Date range filter
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Archive Statistics
        $archiveQuery = Archive::whereBetween('archives.created_at', [$startDate, $endDate]);
        
        if ($role === 'staff') {
            $archiveQuery->where('user_id', $user->id);
        }

        $archiveStats = [
            'total' => $archiveQuery->count(),
            'by_category' => Archive::whereBetween('archives.created_at', [$startDate, $endDate])
                ->select('category_id', DB::raw('count(*) as total'))
                ->groupBy('category_id')
                ->with('category')
                ->get(),
            'by_month' => Archive::whereBetween('archives.created_at', [$startDate, $endDate])
                ->selectRaw('MONTH(archives.created_at) as month, COUNT(*) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];

        // Disposition Statistics
        $dispositionQuery = Disposition::whereBetween('dispositions.created_at', [$startDate, $endDate]);
        
        if ($role === 'staff') {
            $dispositionQuery->where('to_user_id', $user->id);
        }

        $dispositionStats = [
            'total' => $dispositionQuery->count(),
            'pending' => (clone $dispositionQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $dispositionQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $dispositionQuery)->where('status', 'completed')->count(),
            'overdue' => Disposition::whereDate('deadline', '<', Carbon::now())
                ->where('status', '!=', 'completed')
                ->count(),
        ];

        // User Statistics (Admin and Pimpinan only)
        $userStats = [];
        if (in_array($role, ['admin', 'pimpinan'])) {
            $userStats = [
                'total' => User::count(),
                'admin' => User::where('role', 'admin')->count(),
                'staff' => User::where('role', 'staff')->count(),
                'pimpinan' => User::where('role', 'pimpinan')->count(),
                'active' => User::count(),
            ];
        }

        // Chart Data
        $chartData = $this->getChartData($startDate, $endDate, $role);

        return view("{$role}.laporan.index", compact(
            'archiveStats',
            'dispositionStats',
            'userStats',
            'chartData',
            'startDate',
            'endDate'
        ));
    }

    public function arsip(Request $request)
    {
        $role = Auth::user()->role;
        $user = Auth::user();

        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }

        $period = $request->input('period', '1month');
        $dateRange = $this->getDateRangeFromPeriod($period, $request);
        
        $query = Archive::with(['category', 'uploader'])
            ->whereBetween('archives.created_at', [$dateRange['start'], $dateRange['end']]);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        if ($role === 'staff') {
            $query->where('user_id', $user->id);
        }

        $archives = $query->orderBy('archives.created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => $query->count(),
            'by_category' => Archive::whereBetween('archives.created_at', [$dateRange['start'], $dateRange['end']])
                ->select('category_id', DB::raw('count(*) as total'))
                ->groupBy('category_id')
                ->with('category')
                ->get(),
        ];

        return view("{$role}.laporan.arsip", compact('archives', 'stats', 'period', 'dateRange'));
    }

    public function disposisi(Request $request)
    {
        $role = Auth::user()->role;
        $user = Auth::user();

        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }

        $period = $request->input('period', '1month');
        $dateRange = $this->getDateRangeFromPeriod($period, $request);
        
        $query = Disposition::with(['fromUser', 'toUser', 'disposable'])
            ->whereBetween('dispositions.created_at', [$dateRange['start'], $dateRange['end']]);

        if ($request->filled('item_type')) {
            if ($request->item_type === 'arsip') {
                $query->where('disposable_type', 'App\\Models\\Archive');
            } elseif ($request->item_type === 'aset') {
                $query->where('disposable_type', 'App\\Models\\Asset');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($role === 'staff') {
            $query->where('to_user_id', $user->id);
        }

        $dispositions = $query->orderBy('dispositions.created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => (clone $query)->count(),
            'arsip' => (clone $query)->where('disposable_type', 'App\\Models\\Archive')->count(),
            'aset' => (clone $query)->where('disposable_type', 'App\\Models\\Asset')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
        ];

        return view("{$role}.laporan.disposisi", compact('dispositions', 'stats', 'period', 'dateRange'));
    }

    public function aset(Request $request)
    {
        $role = Auth::user()->role;

        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }

        $period = $request->input('period', '1month');
        $dateRange = $this->getDateRangeFromPeriod($period, $request);
        
        $query = Asset::whereBetween('assets.created_at', [$dateRange['start'], $dateRange['end']]);

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $assets = $query->orderBy('assets.created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => Asset::whereBetween('assets.created_at', [$dateRange['start'], $dateRange['end']])->count(),
            'by_kategori' => Asset::whereBetween('assets.created_at', [$dateRange['start'], $dateRange['end']])
                ->select('kategori', DB::raw('count(*) as total'))
                ->groupBy('kategori')
                ->get(),
            'by_kondisi' => Asset::whereBetween('assets.created_at', [$dateRange['start'], $dateRange['end']])
                ->select('kondisi', DB::raw('count(*) as total'))
                ->groupBy('kondisi')
                ->get(),
            'by_status' => Asset::whereBetween('assets.created_at', [$dateRange['start'], $dateRange['end']])
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'by_unit' => Asset::whereBetween('assets.created_at', [$dateRange['start'], $dateRange['end']])
                ->select('unit', DB::raw('count(*) as total'))
                ->whereNotNull('unit')
                ->groupBy('unit')
                ->get(),
        ];

        $categories = Asset::select('kategori')->distinct()->pluck('kategori');
        $units = Asset::select('unit')->distinct()->whereNotNull('unit')->pluck('unit');

        return view("{$role}.laporan.aset", compact('assets', 'stats', 'period', 'dateRange', 'categories', 'units'));
    }

    public function user(Request $request)
    {
        $role = Auth::user()->role;

        if (!in_array($role, ['admin', 'pimpinan'])) {
            abort(403, 'Unauthorized - Admin and Pimpinan only');
        }

        $period = $request->input('period', '1month');
        $dateRange = $this->getDateRangeFromPeriod($period, $request);

        $users = User::orderBy('name')->get()->map(function($user) use ($dateRange) {
            return (object)[
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'unit' => $user->unit,
                'archives_count' => Archive::where('user_id', $user->id)
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->count(),
                'sent_dispositions_count' => Disposition::where('from_user_id', $user->id)
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->count(),
                'received_dispositions_count' => Disposition::where('to_user_id', $user->id)
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->count(),
            ];
        });

        $perPage = 20;
        $currentPage = request()->get('page', 1);
        $items = $users->forPage($currentPage, $perPage);
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $users->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view("{$role}.laporan.user", [
            'users' => $paginator,
            'period' => $period,
            'dateRange' => $dateRange
        ]);
    }

    public function periode(Request $request)
    {
        $role = Auth::user()->role;
        
        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }
        
        $type  = $request->input('type', 'monthly');
        $month = $request->input('month', Carbon::now()->month);
        $year  = $request->input('year', Carbon::now()->year);
        
        if ($type === 'monthly') {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate   = Carbon::create($year, $month, 1)->endOfMonth();
        } else {
            $startDate = Carbon::create($year, 1, 1)->startOfYear();
            $endDate   = Carbon::create($year, 12, 31)->endOfYear();
        }

        // ── Filter berdasarkan role ────────────────────────────────────────
        $archiveQuery     = Archive::whereBetween('archives.created_at', [$startDate, $endDate]);
        $dispositionQuery = Disposition::whereBetween('dispositions.created_at', [$startDate, $endDate]);

        if ($role === 'staff') {
            $archiveQuery->where('archives.user_id', Auth::id());
            $dispositionQuery->where('dispositions.to_user_id', Auth::id());
        }
        // ──────────────────────────────────────────────────────────────────

        $archiveStats = [
            'total'       => (clone $archiveQuery)->count(),
            'by_category' => (clone $archiveQuery)
                ->leftJoin('categories', 'archives.category_id', '=', 'categories.id')
                ->selectRaw('COALESCE(categories.name, "Lainnya") as category, COUNT(*) as total')
                ->groupBy('categories.name')
                ->get(),
        ];

        $dispositionStats = [
            'total'       => (clone $dispositionQuery)->count(),
            'pending'     => (clone $dispositionQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $dispositionQuery)->where('status', 'in_progress')->count(),
            'completed'   => (clone $dispositionQuery)->where('status', 'completed')->count(),
        ];

        if ($type === 'monthly') {
            $chartData = (clone $archiveQuery)
                ->selectRaw('DAY(archives.created_at) as period, COUNT(*) as total')
                ->groupBy('period')
                ->orderBy('period')
                ->pluck('total', 'period');
        } else {
            $chartData = (clone $archiveQuery)
                ->selectRaw('MONTH(archives.created_at) as period, COUNT(*) as total')
                ->groupBy('period')
                ->orderBy('period')
                ->pluck('total', 'period');
        }

        if ($type === 'monthly') {
            $prevStartDate = Carbon::create($year, $month, 1)->subMonth()->startOfMonth();
            $prevEndDate   = Carbon::create($year, $month, 1)->subMonth()->endOfMonth();
        } else {
            $prevStartDate = Carbon::create($year - 1, 1, 1)->startOfYear();
            $prevEndDate   = Carbon::create($year - 1, 12, 31)->endOfYear();
        }

        $prevArchiveQuery     = Archive::whereBetween('archives.created_at', [$prevStartDate, $prevEndDate]);
        $prevDispositionQuery = Disposition::whereBetween('dispositions.created_at', [$prevStartDate, $prevEndDate]);

        if ($role === 'staff') {
            $prevArchiveQuery->where('archives.user_id', Auth::id());
            $prevDispositionQuery->where('dispositions.to_user_id', Auth::id());
        }

        $comparison = [
            'archives' => [
                'current'  => $archiveStats['total'],
                'previous' => $prevArchiveQuery->count(),
            ],
            'dispositions' => [
                'current'  => $dispositionStats['total'],
                'previous' => $prevDispositionQuery->count(),
            ],
        ];

        foreach ($comparison as $key => $data) {
            $comparison[$key]['percentage'] = $data['previous'] > 0
                ? round((($data['current'] - $data['previous']) / $data['previous']) * 100, 1)
                : 0;
        }

        return view("{$role}.laporan.periode", compact(
            'archiveStats', 'dispositionStats', 'chartData',
            'comparison', 'type', 'month', 'year', 'startDate', 'endDate'
        ));
    }

    public function unitKerja(Request $request)
    {
        $role = Auth::user()->role;
        
        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }
        
        $period = $request->input('period', '1month');
        $dateRange = $this->getDateRangeFromPeriod($period, $request);

        $unitsList = User::whereNotNull('unit')
            ->where('unit', '!=', '')
            ->distinct()
            ->pluck('unit');

        $unitData = [];

        foreach ($unitsList as $unitName) {
            $userIds = User::where('unit', $unitName)->pluck('id');

            $totalArchives = Archive::whereIn('user_id', $userIds)
                ->whereBetween('archives.created_at', [$dateRange['start'], $dateRange['end']])
                ->count();

            $totalDispositions = Disposition::whereIn('to_user_id', $userIds)
                ->whereBetween('dispositions.created_at', [$dateRange['start'], $dateRange['end']])
                ->count();

            $completedDispositions = Disposition::whereIn('to_user_id', $userIds)
                ->where('status', 'completed')
                ->whereBetween('dispositions.created_at', [$dateRange['start'], $dateRange['end']])
                ->count();

            $completionRate = $totalDispositions > 0 
                ? round(($completedDispositions / $totalDispositions) * 100, 1) 
                : 0;

            $unitData[] = (object)[
                'unit' => $unitName,
                'total_archives' => $totalArchives,
                'total_dispositions' => $totalDispositions,
                'completed_dispositions' => $completedDispositions,
                'completion_rate' => $completionRate,
            ];
        }

        $sortBy = $request->input('sort_by', 'archives');
        
        if ($sortBy === 'completion_rate') {
            usort($unitData, function($a, $b) {
                return $b->completion_rate <=> $a->completion_rate;
            });
        } else if ($sortBy === 'dispositions') {
            usort($unitData, function($a, $b) {
                return $b->total_dispositions <=> $a->total_dispositions;
            });
        } else {
            usort($unitData, function($a, $b) {
                return $b->total_archives <=> $a->total_archives;
            });
        }

        $units = collect($unitData);

        return view("{$role}.laporan.unit-kerja", compact('units', 'period', 'dateRange'));
    }

    /**
     * PRINT PDF
     */
    /**
     * Laporan Surat Masuk — staff hanya miliknya, pimpinan & admin semua
     */
    public function suratMasuk(Request $request)
    {
        $role = Auth::user()->role;
        $query = IncomingLetter::with(['uploader', 'disposition'])
            ->orderBy('tanggal_diterima', 'desc');

        if ($role === 'staff') {
            $query->where('uploaded_by', Auth::id());
        }
        if ($request->filled('status'))     $query->where('status', $request->status);
        if ($request->filled('sifat'))      $query->where('sifat', $request->sifat);
        if ($request->filled('start_date')) $query->whereDate('tanggal_diterima', '>=', $request->start_date);
        if ($request->filled('end_date'))   $query->whereDate('tanggal_diterima', '<=', $request->end_date);

        $letters = $query->paginate(15)->withQueryString();

        $baseQuery = $role === 'staff'
            ? IncomingLetter::where('uploaded_by', Auth::id())
            : IncomingLetter::query();

        $stats = [
            'total'           => (clone $baseQuery)->count(),
            'belum_disposisi' => (clone $baseQuery)->where('status', 'belum_disposisi')->count(),
            'sudah_disposisi' => (clone $baseQuery)->where('status', 'sudah_disposisi')->count(),
            'selesai'         => (clone $baseQuery)->where('status', 'selesai')->count(),
            'bulan_ini'       => (clone $baseQuery)->whereMonth('tanggal_diterima', now()->month)
                                                   ->whereYear('tanggal_diterima', now()->year)->count(),
        ];

        $totalSurat     = $stats['total'];
        $belumDisposisi = $stats['belum_disposisi'];
        $sudahDisposisi = $stats['sudah_disposisi'];
        $selesai        = $stats['selesai'];

        return view("{$role}.laporan.surat-masuk", compact(
            'letters', 'stats', 'totalSurat', 'belumDisposisi', 'sudahDisposisi', 'selesai'
        ));
    }

    // ========================
    // LAPORAN SURAT KELUAR
    // ========================
    public function suratKeluar(Request $request)
    {
        $role = Auth::user()->role;
        $query = OutgoingLetter::with(['pembuat', 'penandatangan'])
            ->orderBy('tanggal_surat', 'desc');

        if ($role === 'staff') {
            $query->where('dibuat_oleh', Auth::id());
        }
        if ($request->filled('status'))     $query->where('status', $request->status);
        if ($request->filled('sifat'))      $query->where('sifat', $request->sifat);
        if ($request->filled('start_date')) $query->whereDate('tanggal_surat', '>=', $request->start_date);
        if ($request->filled('end_date'))   $query->whereDate('tanggal_surat', '<=', $request->end_date);

        $letters = $query->paginate(15)->withQueryString();

        $baseQuery = $role === 'staff'
            ? OutgoingLetter::where('dibuat_oleh', Auth::id())
            : OutgoingLetter::query();

        $stats = [
            'total'          => (clone $baseQuery)->count(),
            'draft'          => (clone $baseQuery)->where('status', 'draft')->count(),
            'menunggu_tte'   => (clone $baseQuery)->where('status', 'menunggu_tte')->count(),
            'ditandatangani' => (clone $baseQuery)->where('status', 'ditandatangani')->count(),
            'ditolak'        => (clone $baseQuery)->where('status', 'ditolak')->count(),
            'bulan_ini'      => (clone $baseQuery)->whereMonth('tanggal_surat', now()->month)
                                                   ->whereYear('tanggal_surat', now()->year)->count(),
        ];

        return view("{$role}.laporan.surat-keluar", compact('letters', 'stats'));
    }

    // ========================
    // LAPORAN REKAP AGENDA SURAT (MASUK & KELUAR)
    // ========================
    public function agendaSurat(Request $request)
    {
        $role = Auth::user()->role;

        $masukQuery = IncomingLetter::query();
        $keluarQuery = OutgoingLetter::query();

        if ($role === 'staff') {
            $masukQuery->where('uploaded_by', Auth::id());
            $keluarQuery->where('dibuat_oleh', Auth::id());
        }

        if ($request->filled('start_date')) {
            $masukQuery->whereDate('tanggal_diterima', '>=', $request->start_date);
            $keluarQuery->whereDate('tanggal_surat', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $masukQuery->whereDate('tanggal_diterima', '<=', $request->end_date);
            $keluarQuery->whereDate('tanggal_surat', '<=', $request->end_date);
        }

        $masuk = $masukQuery->get()->map(function ($item) {
            return (object) [
                'jenis'        => 'masuk',
                'nomor_agenda' => $item->nomor_agenda,
                'tanggal'      => $item->tanggal_diterima,
                'pihak'        => $item->pengirim,
                'perihal'      => $item->perihal,
                'status'       => $item->status,
            ];
        });

        $keluar = $keluarQuery->get()->map(function ($item) {
            return (object) [
                'jenis'        => 'keluar',
                'nomor_agenda' => $item->nomor_agenda,
                'tanggal'      => $item->tanggal_surat,
                'pihak'        => $item->tujuan,
                'perihal'      => $item->perihal,
                'status'       => $item->status,
            ];
        });

        $agenda = $masuk->concat($keluar)->sortByDesc('tanggal')->values();

        $stats = [
            'total_masuk'  => $masuk->count(),
            'total_keluar' => $keluar->count(),
            'total'        => $agenda->count(),
        ];

        return view("{$role}.laporan.agenda-surat", compact('agenda', 'stats'));
    }

    public function printPdf(Request $request)
    {
        $type = $request->input('type', 'summary');
        
        $allowedTypes = ['summary', 'arsip', 'disposisi', 'aset', 'user', 'unit', 'penyusutan', 'peminjaman', 'maintenance', 'surat-masuk', 'laporan-surat-keluar', 'pemusnahan', 'agenda-surat', 'beban-kerja-pimpinan'];
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'Report type not found');
        }
        
        $data = $this->getExportData($type, $request);

        // Print PDF = TANPA TTE (preview saja)
        $data['signature']   = null;
        $data['qrSvg']       = null;
        $data['validasiUrl'] = null;

        $pdf = Pdf::loadView("reports.pdf.{$type}", $data)
            ->setPaper('a4', 'portrait');
        
        return $pdf->stream("laporan_{$type}_" . date('Y-m-d') . ".pdf");
    }

    /**
     * DOWNLOAD PDF
     */
    public function exportPdf(Request $request)
    {
        $type = $request->input('type', 'summary');
        
        $allowedTypes = ['summary', 'arsip', 'disposisi', 'aset', 'user', 'unit', 'penyusutan', 'peminjaman', 'maintenance', 'surat-masuk', 'laporan-surat-keluar', 'pemusnahan', 'agenda-surat', 'beban-kerja-pimpinan'];
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'Report type not found');
        }
        
        $data = $this->getExportData($type, $request);

        $judulMap = [
            'arsip'       => 'Laporan Arsip Digital',
            'disposisi'   => 'Laporan Disposisi',
            'aset'        => 'Laporan Manajemen Aset',
            'user'        => 'Laporan Pengguna',
            'unit'        => 'Laporan Unit Kerja',
            'penyusutan'  => 'Laporan Penyusutan Aset',
            'peminjaman'  => 'Laporan Peminjaman Aset',
            'maintenance' => 'Laporan Pemeliharaan Aset',
            'surat-masuk' => 'Laporan Surat Masuk',
            'pemusnahan'  => 'Laporan Pemusnahan Aset',
            'agenda-surat' => 'Laporan Rekap Agenda Surat',
            'laporan-surat-keluar' => 'Laporan Surat Keluar',
            'beban-kerja-pimpinan' => 'Laporan Beban Kerja Validasi Pimpinan',
            'summary'     => 'Laporan Ringkasan',
        ];
        $signature = DocumentSignature::generateFor(
            documentType:  $type,
            documentTitle: $judulMap[$type] ?? 'Laporan ' . ucfirst($type),
            signedBy:      'Aris Saputera, S.STP.,MSi',
            signedByTitle: 'Kepala Dinas',
        );
        $validasiUrl         = url('/validasi/' . $signature->token);
        $data['signature']   = $signature;
        $data['qrSvg']   = $this->generateQrDataUri($validasiUrl);
        $data['validasiUrl'] = $validasiUrl;
        // ────────────────────────────────────────────────────────────────────

        $pdf = Pdf::loadView("reports.pdf.{$type}", $data);
        
        return $pdf->download("laporan_{$type}_" . date('Y-m-d') . ".pdf");
    }

    /**
     * Generate QR Code sebagai data URI base64 PNG
     */
    public function generateQrDataUri(string $url): string
    {
        try {
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(150)
                ->margin(1)
                ->errorCorrection('L')
                ->generate($url);

            // Simpan ke file sementara di storage/app/public/tte/
            $filename = 'tte_' . md5($url) . '.svg';
            $path     = 'tte/' . $filename;
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, $qrCode);

            // Kembalikan path absolut untuk DomPDF
            return \Illuminate\Support\Facades\Storage::disk('public')->path($path);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('QR TTE Error: ' . $e->getMessage());
            return '';
        }
    }

    public function exportExcel(Request $request)
    {
        $type = $request->input('type', 'arsip');
        
        return back()->with('info', 'Fitur export Excel akan segera tersedia');
    }

    private function getDateRangeFromPeriod($period, Request $request)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case '1month':
                return [
                    'start' => $now->copy()->subMonth()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'label' => '1 Bulan Terakhir'
                ];
            
            case '3months':
                return [
                    'start' => $now->copy()->subMonths(3)->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'label' => '3 Bulan Terakhir'
                ];
            
            case '6months':
                return [
                    'start' => $now->copy()->subMonths(6)->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'label' => '6 Bulan Terakhir'
                ];
            
            case '1year':
                return [
                    'start' => $now->copy()->subYear()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'label' => '1 Tahun Terakhir'
                ];
            
            case 'custom':
                $start = $request->input('start_date', $now->copy()->startOfMonth());
                $end = $request->input('end_date', $now->copy()->endOfMonth());
                
                return [
                    'start' => Carbon::parse($start)->startOfDay(),
                    'end' => Carbon::parse($end)->endOfDay(),
                    'label' => 'Custom Range'
                ];
            
            default:
                return [
                    'start' => $now->copy()->startOfMonth()->startOfDay(),
                    'end' => $now->copy()->endOfMonth()->endOfDay(),
                    'label' => 'Bulan Ini'
                ];
        }
    }

    private function getChartData($startDate, $endDate, $role)
    {
        $archivesPerMonth = Archive::whereBetween('archives.created_at', [$startDate, $endDate])
            ->selectRaw('MONTH(archives.created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $dispositionsByStatus = Disposition::whereBetween('dispositions.created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $archivesByCategory = Archive::whereBetween('archives.created_at', [$startDate, $endDate])
            ->join('categories', 'archives.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, COUNT(*) as total')
            ->groupBy('categories.name')
            ->pluck('total', 'category');

        return [
            'archives_per_month' => $archivesPerMonth,
            'dispositions_by_status' => $dispositionsByStatus,
            'archives_by_category' => $archivesByCategory,
        ];
    }

    public function getExportData($type, $request)
    {
        $data = [];

        switch ($type) {
            case 'summary':
                $data['archives'] = Archive::count();
                $data['dispositions'] = Disposition::count();
                $data['users'] = User::count();
                $data['assets'] = Asset::count();
                
                $data['archive_stats'] = Archive::join('categories', 'archives.category_id', '=', 'categories.id')
                    ->selectRaw('categories.name as category, COUNT(*) as total')
                    ->groupBy('categories.name')
                    ->get()
                    ->map(function($item) {
                        return [
                            'category' => $item->category,
                            'total' => $item->total
                        ];
                    })
                    ->toArray();
                
                $data['disposition_stats'] = Disposition::selectRaw('status, COUNT(*) as total')
                    ->groupBy('status')
                    ->get()
                    ->map(function($item) {
                        return [
                            'status' => $item->status,
                            'total' => $item->total
                        ];
                    })
                    ->toArray();
                
                $data['asset_stats'] = Asset::selectRaw('kategori, COUNT(*) as total')
                    ->groupBy('kategori')
                    ->get()
                    ->map(function($item) {
                        return [
                            'kategori' => $item->kategori,
                            'total' => $item->total
                        ];
                    })
                    ->toArray();
                
                $data['user_stats'] = User::selectRaw('role, COUNT(*) as total')
                    ->groupBy('role')
                    ->get()
                    ->map(function($item) {
                        return [
                            'role' => $item->role,
                            'total' => $item->total
                        ];
                    })
                    ->toArray();
                break;

            case 'arsip':
                $period = $request->input('period', '1month');
                $dateRange = $this->getDateRangeFromPeriod($period, $request);
                
                $query = Archive::with(['category', 'uploader'])
                    ->whereBetween('archives.created_at', [$dateRange['start'], $dateRange['end']]);
                
                if ($request->filled('category_id')) {
                    $query->where('category_id', $request->category_id);
                }
                
                $data['archives'] = $query->orderBy('archives.created_at', 'desc')->get();
                $data['period'] = $dateRange['label'];
                break;

            case 'disposisi':
                $period = $request->input('period', '1month');
                $dateRange = $this->getDateRangeFromPeriod($period, $request);
                
                $query = Disposition::with(['fromUser', 'toUser', 'disposable'])
                    ->whereBetween('dispositions.created_at', [$dateRange['start'], $dateRange['end']]);
                
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                
                if ($request->filled('item_type')) {
                    if ($request->item_type === 'arsip') {
                        $query->where('disposable_type', 'App\\Models\\Archive');
                    } elseif ($request->item_type === 'aset') {
                        $query->where('disposable_type', 'App\\Models\\Asset');
                    }
                }
                
                $data['dispositions'] = $query->orderBy('dispositions.created_at', 'desc')->get();
                $data['period'] = $dateRange['label'];
                break;

            case 'aset':
                $period = $request->input('period', '1month');
                $dateRange = $this->getDateRangeFromPeriod($period, $request);
                
                $query = Asset::whereBetween('assets.created_at', [$dateRange['start'], $dateRange['end']]);
                
                if ($request->filled('kategori')) {
                    $query->where('kategori', $request->kategori);
                }
                
                $data['assets'] = $query->orderBy('assets.created_at', 'desc')->get();
                $data['period'] = $dateRange['label'];
                break;

            
            case 'penyusutan':
                $data['assets'] = Asset::whereNotNull('harga_pembelian')
                    ->where('harga_pembelian', '>', 0)
                    ->orderBy('tanggal_pembelian', 'desc')
                    ->get();
                
                $data['totalAsetAwal'] = $data['assets']->sum('harga_pembelian');
                $data['totalNilaiBuku'] = $data['assets']->sum('nilai_buku');
                break;

           
            case 'peminjaman':
                $period = $request->input('period', '1month');
                $dateRange = $this->getDateRangeFromPeriod($period, $request);
                
                $query = AssetBorrow::with(['asset', 'borrower'])
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                
                if (Auth::user()->role === 'staff') {
                    $query->where('borrower_id', Auth::id());
                }
                
                $data['borrows'] = $query->orderBy('created_at', 'desc')->get();
                $data['period'] = $dateRange['label'];
                break;

            case 'surat-masuk':
                $role = Auth::user()->role;
                $smQuery = IncomingLetter::with(['uploader', 'disposition'])
                    ->orderBy('tanggal_diterima', 'desc');
                if ($role === 'staff') {
                    $smQuery->where('uploaded_by', Auth::id());
                }
                if ($request->filled('status'))     $smQuery->where('status', $request->status);
                if ($request->filled('start_date')) $smQuery->whereDate('tanggal_diterima', '>=', $request->start_date);
                if ($request->filled('end_date'))   $smQuery->whereDate('tanggal_diterima', '<=', $request->end_date);
                $data['letters'] = $smQuery->get();
                $data['period']  = 'Hingga ' . now()->format('d M Y');
                break;

            case 'maintenance':
                $data['assets'] = Asset::whereIn('status', ['maintenance', 'rusak'])
                                       ->orWhereIn('kondisi', ['kurang', 'rusak'])
                                       ->orderBy('updated_at', 'desc')->get();
                $data['period'] = 'Hingga ' . now()->format('d M Y');
                break;

            case 'pemusnahan':
                $role = Auth::user()->role;
                $destructionQuery = AssetDestruction::with(['asset', 'pengaju', 'penyetuju'])
                    ->orderBy('created_at', 'desc');

                if ($role === 'staff') {
                    $destructionQuery->where('diajukan_oleh', Auth::id());
                }

                if ($request->filled('status')) {
                    $destructionQuery->where('status', $request->status);
                }

                $data['destructions'] = $destructionQuery->get();
                $data['period'] = 'Hingga ' . now()->format('d M Y');
                break;

            case 'agenda-surat':
                $role = Auth::user()->role;
                $masukQuery = IncomingLetter::query();
                $keluarQuery = OutgoingLetter::query();

                if ($role === 'staff') {
                    $masukQuery->where('uploaded_by', Auth::id());
                    $keluarQuery->where('dibuat_oleh', Auth::id());
                }

                $masuk = $masukQuery->get()->map(fn($i) => (object)[
                    'jenis' => 'masuk', 'nomor_agenda' => $i->nomor_agenda,
                    'tanggal' => $i->tanggal_diterima, 'pihak' => $i->pengirim,
                    'perihal' => $i->perihal, 'status' => $i->status,
                ]);
                $keluar = $keluarQuery->get()->map(fn($i) => (object)[
                    'jenis' => 'keluar', 'nomor_agenda' => $i->nomor_agenda,
                    'tanggal' => $i->tanggal_surat, 'pihak' => $i->tujuan,
                    'perihal' => $i->perihal, 'status' => $i->status,
                ]);

                $data['agenda'] = $masuk->concat($keluar)->sortByDesc('tanggal')->values();
                $data['period'] = 'Hingga ' . now()->format('d M Y');
                break;

            case 'beban-kerja-pimpinan':
                $period = $request->input('period', '1month');
                $dateRange = $this->getDateRangeFromPeriod($period, $request);

                $laporanValidasi = LaporanPengajuan::with(['pengaju', 'validator'])
                    ->whereBetween('diajukan_at', [$dateRange['start'], $dateRange['end']])
                    ->whereNotNull('divalidasi_at')
                    ->get()
                    ->map(function ($item) {
                        $item->waktu_proses_jam = $item->diajukan_at && $item->divalidasi_at
                            ? round($item->diajukan_at->diffInMinutes($item->divalidasi_at) / 60, 1)
                            : null;
                        return $item;
                    });

                $data['laporanStats'] = [
                    'total_divalidasi' => $laporanValidasi->count(),
                    'disetujui'        => $laporanValidasi->where('status', 'disetujui')->count(),
                    'ditolak'          => $laporanValidasi->where('status', 'ditolak')->count(),
                    'rata_rata_jam'    => round($laporanValidasi->whereNotNull('waktu_proses_jam')->avg('waktu_proses_jam') ?? 0, 1),
                    'tercepat_jam'     => $laporanValidasi->whereNotNull('waktu_proses_jam')->min('waktu_proses_jam'),
                    'terlama_jam'      => $laporanValidasi->whereNotNull('waktu_proses_jam')->max('waktu_proses_jam'),
                ];

                $data['laporanPerJenis'] = $laporanValidasi->groupBy('jenis_laporan')->map(function ($group) {
                    return [
                        'total'         => $group->count(),
                        'rata_rata_jam' => round($group->whereNotNull('waktu_proses_jam')->avg('waktu_proses_jam') ?? 0, 1),
                    ];
                });

                $pimpinanIds = User::where('role', 'pimpinan')->pluck('id');
                $disposisiQuery = Disposition::whereIn('to_user_id', $pimpinanIds)
                    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                $disposisiSelesai = (clone $disposisiQuery)->whereNotNull('completed_at')->get()
                    ->map(function ($item) {
                        $item->waktu_proses_jam = round($item->created_at->diffInMinutes($item->completed_at) / 60, 1);
                        return $item;
                    });

                $data['disposisiStats'] = [
                    'total_diterima' => (clone $disposisiQuery)->count(),
                    'total_selesai'  => $disposisiSelesai->count(),
                    'rata_rata_jam'  => round($disposisiSelesai->avg('waktu_proses_jam') ?? 0, 1),
                ];

                $data['laporanValidasi'] = $laporanValidasi;
                $data['dateRange'] = $dateRange;
                $data['period']    = $dateRange['label'] ?? ('Periode: ' . $dateRange['start']->format('d M Y') . ' - ' . $dateRange['end']->format('d M Y'));
                break;

            case 'laporan-surat-keluar':
                $role = Auth::user()->role;
                $skQuery = OutgoingLetter::with(['pembuat', 'penandatangan'])
                    ->orderBy('tanggal_surat', 'desc');

                if ($role === 'staff') {
                    $skQuery->where('dibuat_oleh', Auth::id());
                }

                $data['letters'] = $skQuery->get();
                $data['period']  = 'Hingga ' . now()->format('d M Y');
                break;

            case 'user':
                $period = $request->input('period', '1month');
                $dateRange = $this->getDateRangeFromPeriod($period, $request);
                
                $users = User::orderBy('name')->get()->map(function($user) use ($dateRange) {
                    return (object)[
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'unit' => $user->unit,
                        'archives_count' => Archive::where('user_id', $user->id)
                            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                            ->count(),
                        'sent_dispositions_count' => Disposition::where('from_user_id', $user->id)
                            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                            ->count(),
                        'received_dispositions_count' => Disposition::where('to_user_id', $user->id)
                            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                            ->count(),
                    ];
                });
                
                $data['users'] = $users;
                $data['period'] = $dateRange['label'];
                $data['start_date'] = $dateRange['start'];
                $data['end_date'] = $dateRange['end'];
                break;

            case 'unit':
                $period = $request->input('period', '1month');
                $dateRange = $this->getDateRangeFromPeriod($period, $request);
                
                $unitsList = User::whereNotNull('unit')
                    ->where('unit', '!=', '')
                    ->distinct()
                    ->pluck('unit');

                $unitData = [];

                foreach ($unitsList as $unitName) {
                    $userIds = User::where('unit', $unitName)->pluck('id');

                    $totalArchives = Archive::whereIn('user_id', $userIds)
                        ->whereBetween('archives.created_at', [$dateRange['start'], $dateRange['end']])
                        ->count();

                    $totalDispositions = Disposition::whereIn('to_user_id', $userIds)
                        ->whereBetween('dispositions.created_at', [$dateRange['start'], $dateRange['end']])
                        ->count();

                    $completedDispositions = Disposition::whereIn('to_user_id', $userIds)
                        ->where('status', 'completed')
                        ->whereBetween('dispositions.created_at', [$dateRange['start'], $dateRange['end']])
                        ->count();

                    $completionRate = $totalDispositions > 0 
                        ? round(($completedDispositions / $totalDispositions) * 100, 1) 
                        : 0;

                    $unitData[] = [
                        'unit' => $unitName,
                        'total_archives' => $totalArchives,
                        'total_dispositions' => $totalDispositions,
                        'completed_dispositions' => $completedDispositions,
                        'completion_rate' => $completionRate,
                    ];
                }

                $sortBy = $request->input('sort_by', 'archives');
                
                if ($sortBy === 'completion_rate') {
                    usort($unitData, function($a, $b) {
                        return $b['completion_rate'] <=> $a['completion_rate'];
                    });
                } else if ($sortBy === 'dispositions') {
                    usort($unitData, function($a, $b) {
                        return $b['total_dispositions'] <=> $a['total_dispositions'];
                    });
                } else {
                    usort($unitData, function($a, $b) {
                        return $b['total_archives'] <=> $a['total_archives'];
                    });
                }

                $data['units'] = $unitData;
                $data['period'] = $dateRange['label'];
                $data['start_date'] = $dateRange['start'];
                $data['end_date'] = $dateRange['end'];
                break;
        }

        return $data;
    }

    /**
     * ✅ LAPORAN KE-6: Laporan Penyusutan Aset
     */
    public function penyusutan(Request $request)
    {
        $role = Auth::user()->role;
        
        if (!in_array($role, ['admin', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }

        $assets = Asset::whereNotNull('harga_pembelian')
                       ->where('harga_pembelian', '>', 0)
                       ->orderBy('tanggal_pembelian', 'desc')
                       ->get();

        $totalNilaiBuku = $assets->sum('nilai_buku');
        $totalAsetAwal = $assets->sum('harga_pembelian');

        return view("{$role}.laporan.penyusutan", compact('assets', 'totalNilaiBuku', 'totalAsetAwal'));
    }

    /**
     * ✅ LAPORAN KE-7: Laporan Peminjaman Aset
     */
    public function peminjaman(Request $request)
    {
        $role = Auth::user()->role;
        $user = Auth::user();

        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }

        $period = $request->input('period', '1month');
        $dateRange = $this->getDateRangeFromPeriod($period, $request);

        $query = AssetBorrow::with(['asset', 'borrower'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);

        if ($role === 'staff') {
            $query->where('borrower_id', $user->id);
        }

        $borrows = $query->orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => (clone $query)->count(),
            'dipinjam' => (clone $query)->whereIn('status', ['approved', 'borrowed'])->count(),
            'dikembalikan' => (clone $query)->where('status', 'returned')->count(),
            'ditolak' => (clone $query)->where('status', 'rejected')->count(),
            'terlambat' => (clone $query)->where('status', 'overdue')->count(),
        ];

        return view("{$role}.laporan.peminjaman", compact('borrows', 'stats', 'period', 'dateRange'));
    }
    public function maintenance(Request $request)
    {
        $role = Auth::user()->role;
        if (!in_array($role, ['admin', 'pimpinan'])) abort(403, 'Unauthorized');

        $assets = Asset::whereIn('status', ['maintenance', 'rusak'])
                       ->orWhereIn('kondisi', ['kurang', 'rusak'])
                       ->orderBy('updated_at', 'desc')
                       ->get();

        $stats = [
            'total' => $assets->count(),
            'rusak_berat' => $assets->where('kondisi', 'rusak')->count(),
            'perlu_perbaikan' => $assets->where('kondisi', 'kurang')->count(),
            'sedang_maintenance' => $assets->where('status', 'maintenance')->count(),
        ];

        return view("{$role}.laporan.maintenance", compact('assets', 'stats'));
    }

    // ========================
    // LAPORAN PEMUSNAHAN ASET
    // ========================
    public function pemusnahan(Request $request)
    {
        $role = Auth::user()->role;

        if (!in_array($role, ['admin', 'staff', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }

        $query = AssetDestruction::with(['asset', 'pengaju', 'penyetuju'])
            ->orderBy('created_at', 'desc');

        if ($role === 'staff') {
            $query->where('diajukan_oleh', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_usulan', [$request->start_date, $request->end_date]);
        }

        $destructions = $query->get();

        $statsBase = AssetDestruction::query();
        if ($role === 'staff') {
            $statsBase->where('diajukan_oleh', Auth::id());
        }

        $stats = [
            'total'     => (clone $statsBase)->count(),
            'menunggu'  => (clone $statsBase)->where('status', 'menunggu')->count(),
            'disetujui' => (clone $statsBase)->where('status', 'disetujui')->count(),
            'ditolak'   => (clone $statsBase)->where('status', 'ditolak')->count(),
        ];

        return view("{$role}.laporan.pemusnahan", compact('destructions', 'stats'));
    }

    // ========================
    // LAPORAN BEBAN KERJA VALIDASI PIMPINAN
    // ========================
    public function bebanKerjaPimpinan(Request $request)
    {
        $role = Auth::user()->role;

        if (!in_array($role, ['admin', 'pimpinan'])) {
            abort(403, 'Unauthorized');
        }

        $period = $request->input('period', '1month');
        $dateRange = $this->getDateRangeFromPeriod($period, $request);

        // ── Validasi Laporan (TTE) oleh Pimpinan ──
        $laporanQuery = LaporanPengajuan::with(['pengaju', 'validator'])
            ->whereBetween('diajukan_at', [$dateRange['start'], $dateRange['end']])
            ->whereNotNull('divalidasi_at');

        $laporanValidasi = $laporanQuery->get()->map(function ($item) {
            $item->waktu_proses_jam = $item->diajukan_at && $item->divalidasi_at
                ? round($item->diajukan_at->diffInMinutes($item->divalidasi_at) / 60, 1)
                : null;
            return $item;
        });

        $laporanStats = [
            'total_divalidasi'  => $laporanValidasi->count(),
            'disetujui'         => $laporanValidasi->where('status', 'disetujui')->count(),
            'ditolak'           => $laporanValidasi->where('status', 'ditolak')->count(),
            'rata_rata_jam'     => $laporanValidasi->whereNotNull('waktu_proses_jam')->avg('waktu_proses_jam'),
            'tercepat_jam'      => $laporanValidasi->whereNotNull('waktu_proses_jam')->min('waktu_proses_jam'),
            'terlama_jam'       => $laporanValidasi->whereNotNull('waktu_proses_jam')->max('waktu_proses_jam'),
        ];

        $laporanPerJenis = $laporanValidasi->groupBy('jenis_laporan')->map(function ($group) {
            return [
                'total' => $group->count(),
                'rata_rata_jam' => round($group->whereNotNull('waktu_proses_jam')->avg('waktu_proses_jam') ?? 0, 1),
            ];
        });

        // ── Disposisi yang diterima & diselesaikan Pimpinan ──
        $pimpinanIds = User::where('role', 'pimpinan')->pluck('id');

        $disposisiQuery = Disposition::whereIn('to_user_id', $pimpinanIds)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);

        $disposisiSelesai = (clone $disposisiQuery)->whereNotNull('completed_at')->get()
            ->map(function ($item) {
                $item->waktu_proses_jam = round($item->created_at->diffInMinutes($item->completed_at) / 60, 1);
                return $item;
            });

        $disposisiStats = [
            'total_diterima'   => (clone $disposisiQuery)->count(),
            'total_selesai'    => $disposisiSelesai->count(),
            'rata_rata_jam'    => round($disposisiSelesai->avg('waktu_proses_jam') ?? 0, 1),
        ];

        return view("{$role}.laporan.beban-kerja-pimpinan", compact(
            'laporanValidasi', 'laporanStats', 'laporanPerJenis',
            'disposisiStats', 'dateRange', 'period'
        ));
    }
}