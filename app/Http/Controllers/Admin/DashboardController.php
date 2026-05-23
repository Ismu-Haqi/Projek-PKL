<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\Disposition;
use App\Models\Asset;
use App\Models\AssetBorrow;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================================
        // 1. STATISTIK CARDS
        // ============================================
        
        // Total Arsip
        $totalArchives = Archive::count();
        $previousMonthArchives = Archive::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        $archivesGrowth = $previousMonthArchives > 0 
            ? round((($totalArchives - $previousMonthArchives) / $previousMonthArchives) * 100, 1)
            : 0;

        // Arsip Bulan Ini
        $currentMonthArchives = Archive::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $lastMonthArchives = Archive::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        $monthlyGrowth = $lastMonthArchives > 0 
            ? round((($currentMonthArchives - $lastMonthArchives) / $lastMonthArchives) * 100, 1)
            : 0;

        // Pengguna Aktif
        $activeUsers = User::where('is_active', true)->count();
        $totalUsers = User::count();
        $activeUsersPercentage = $totalUsers > 0 
            ? round(($activeUsers / $totalUsers) * 100, 1)
            : 0;

        // Disposisi (Pending + In Progress)
        $pendingDispositions = Disposition::whereIn('status', ['pending', 'in_progress'])->count();
        $totalDispositions = Disposition::count();
        $dispositionPercentage = $totalDispositions > 0 
            ? round(($pendingDispositions / $totalDispositions) * 100, 1)
            : 0;

        // ============================================
        // 2. TREN PENGARSIPAN BULANAN (6 Bulan)
        // ============================================
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Archive::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $monthlyTrend[] = [
                'month' => $date->format('M'),
                'year' => $date->format('Y'),
                'count' => $count
            ];
        }

        // ============================================
        // 3. DISTRIBUSI KATEGORI ARSIP
        // ============================================
        $categoryDistribution = Archive::select('category_id', DB::raw('count(*) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category ? $item->category->name : 'Tanpa Kategori',
                    'total' => $item->total,
                    'color' => $item->category ? $item->category->color : '#6B7280'
                ];
            });

        // ============================================
        // 4. ✅ AKTIVITAS TERKINI - WITH ASSET ACTIVITIES
        // ============================================
        $recentActivities = collect();

        // Arsip terbaru (ambil 6)
        $recentArchives = Archive::with('uploader')
            ->latest()
            ->limit(6)
            ->get()
            ->map(function ($archive) {
                return [
                    'type' => 'upload',
                    'user' => $archive->uploader->name ?? 'Unknown',
                    'title' => $archive->judul,
                    'time' => $archive->created_at->diffForHumans(),
                    'timestamp' => $archive->created_at,
                    'color' => 'blue'
                ];
            });

        // Disposisi terbaru (ambil 6)
        $recentDispositions = Disposition::with(['fromUser', 'disposable'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(function ($disposition) {
                // Get title from disposable (arsip or aset)
                $title = 'Disposisi';
                if ($disposition->disposable_type === 'App\Models\Archive' && $disposition->disposable) {
                    $title = $disposition->disposable->judul ?? $disposition->subject;
                } elseif ($disposition->disposable_type === 'App\Models\Asset' && $disposition->disposable) {
                    $title = $disposition->disposable->nama ?? $disposition->subject;
                } else {
                    $title = $disposition->subject;
                }

                return [
                    'type' => 'disposition',
                    'user' => $disposition->fromUser->name ?? 'Unknown',
                    'title' => $title,
                    'time' => $disposition->created_at->diffForHumans(),
                    'timestamp' => $disposition->created_at,
                    'color' => 'green'
                ];
            });

        // ✅ NEW: Aktivitas Peminjaman Aset (ambil 6)
        $recentAssetBorrows = AssetBorrow::with(['borrower', 'asset'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(function ($borrow) {
                // Tentukan tipe aktivitas berdasarkan status
                $activityType = 'asset_borrow_pending';
                $activityText = 'mengajukan peminjaman aset';
                $color = 'yellow';

                if ($borrow->status === 'approved') {
                    $activityType = 'asset_borrow_approved';
                    $activityText = 'peminjaman aset disetujui';
                    $color = 'green';
                } elseif ($borrow->status === 'borrowed') {
                    $activityType = 'asset_borrowed';
                    $activityText = 'meminjam aset';
                    $color = 'blue';
                } elseif ($borrow->status === 'returned') {
                    $activityType = 'asset_returned';
                    $activityText = 'mengembalikan aset';
                    $color = 'purple';
                } elseif ($borrow->status === 'rejected') {
                    $activityType = 'asset_borrow_rejected';
                    $activityText = 'peminjaman aset ditolak';
                    $color = 'red';
                } elseif ($borrow->status === 'overdue') {
                    $activityType = 'asset_overdue';
                    $activityText = 'terlambat mengembalikan aset';
                    $color = 'red';
                }

                return [
                    'type' => $activityType,
                    'user' => $borrow->borrower->name ?? 'Unknown',
                    'title' => $borrow->asset->nama ?? 'Aset',
                    'activity_text' => $activityText,
                    'time' => $borrow->created_at->diffForHumans(),
                    'timestamp' => $borrow->created_at,
                    'color' => $color
                ];
            });

        // ✅ NEW: Aktivitas Aset (create, update) (ambil 6)
        $recentAssets = Asset::latest()
            ->limit(6)
            ->get()
            ->map(function ($asset) {
                // Cek apakah baru dibuat (kurang dari 1 jam)
                $isNew = $asset->created_at->diffInHours(Carbon::now()) < 1;
                
                return [
                    'type' => $isNew ? 'asset_created' : 'asset_updated',
                    'user' => $asset->penanggung_jawab ?? 'Admin',
                    'title' => $asset->nama,
                    'activity_text' => $isNew ? 'menambahkan aset baru' : 'memperbarui aset',
                    'time' => $asset->created_at->diffForHumans(),
                    'timestamp' => $asset->created_at,
                    'color' => $isNew ? 'indigo' : 'gray'
                ];
            });

        // Gabungkan semua aktivitas dan urutkan - ambil 10 teratas
        $recentActivities = $recentArchives
            ->concat($recentDispositions)
            ->concat($recentAssetBorrows)
            ->concat($recentAssets)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();

        // ============================================
        // 5. ARSIP TERBARU (10 Terakhir untuk Auto-Scroll)
        // ============================================
        $latestArchives = Archive::with(['category', 'uploader'])
            ->latest()
            ->limit(10)
            ->get();

        // ============================================
        // 6. DISPOSISI MENDESAK (Deadline < 7 hari)
        // ============================================
        $urgentDispositions = Disposition::with(['disposable', 'toUser'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->where('deadline', '<=', Carbon::now()->addDays(7))
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        // ============================================
        // 7. TOP CONTRIBUTORS (User dengan arsip terbanyak)
        // ============================================
        $topContributors = User::leftJoin('archives', 'users.id', '=', 'archives.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.username',
                'users.role',
                'users.unit',
                'users.avatar',
                DB::raw('COUNT(archives.id) as archives_count')
            )
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'users.username',
                'users.role',
                'users.unit',
                'users.avatar'
            )
            ->orderByDesc('archives_count')
            ->limit(5)
            ->get();

        // ============================================
        // REMINDER PERAWATAN H-7
        // ============================================
        $asetJatuhTempoPerawatan = Asset::jatuhTempoPerawatan(7)
            ->get(['id', 'kode_asset', 'nama', 'kategori', 'unit', 'lokasi',
                   'jadwal_perawatan_selanjutnya', 'jenis_perawatan']);

        $asetPerawatanTerlambat = Asset::perawatanTerlambat()
            ->get(['id', 'kode_asset', 'nama', 'kategori', 'unit', 'lokasi',
                   'jadwal_perawatan_selanjutnya', 'jenis_perawatan']);

        // ============================================
        // RETURN TO VIEW
        // ============================================
        return view('admin.dashboard', compact(
            // Stats
            'totalArchives',
            'archivesGrowth',
            'currentMonthArchives',
            'monthlyGrowth',
            'activeUsers',
            'activeUsersPercentage',
            'pendingDispositions',
            'dispositionPercentage',
            
            // Charts
            'monthlyTrend',
            'categoryDistribution',
            
            // Activities
            'recentActivities',
            'latestArchives',
            'urgentDispositions',
            'topContributors',

            // Reminder Perawatan
            'asetJatuhTempoPerawatan',
            'asetPerawatanTerlambat'
        ));
    }

    /**
     * ✅ UPDATED: Get dashboard data via AJAX (untuk real-time updates & auto-refresh)
     */
    public function getData(Request $request)
    {
        try {
            $type = $request->get('type', 'all');

            // Jika request tanpa parameter type, return semua data untuk auto-refresh
            if ($type === 'all' || !$request->has('type')) {
                // Get Recent Activities - WITH ASSET ACTIVITIES
                $recentActivities = collect();
                
                // Arsip terbaru (ambil 6)
                $recentArchives = Archive::with('uploader')
                    ->latest()
                    ->limit(6)
                    ->get()
                    ->map(function ($archive) {
                        return [
                            'type' => 'upload',
                            'user' => $archive->uploader->name ?? 'Unknown',
                            'title' => $archive->judul,
                            'time' => $archive->created_at->diffForHumans(),
                            'timestamp' => $archive->created_at,
                            'color' => 'blue'
                        ];
                    });

                // Disposisi terbaru (ambil 6)
                $recentDispositions = Disposition::with(['fromUser', 'disposable'])
                    ->latest()
                    ->limit(6)
                    ->get()
                    ->map(function ($disposition) {
                        $title = 'Disposisi';
                        if ($disposition->disposable_type === 'App\Models\Archive' && $disposition->disposable) {
                            $title = $disposition->disposable->judul ?? $disposition->subject;
                        } elseif ($disposition->disposable_type === 'App\Models\Asset' && $disposition->disposable) {
                            $title = $disposition->disposable->nama ?? $disposition->subject;
                        } else {
                            $title = $disposition->subject;
                        }

                        return [
                            'type' => 'disposition',
                            'user' => $disposition->fromUser->name ?? 'Unknown',
                            'title' => $title,
                            'time' => $disposition->created_at->diffForHumans(),
                            'timestamp' => $disposition->created_at,
                            'color' => 'green'
                        ];
                    });

                // ✅ NEW: Aktivitas Peminjaman Aset
                $recentAssetBorrows = AssetBorrow::with(['borrower', 'asset'])
                    ->latest()
                    ->limit(6)
                    ->get()
                    ->map(function ($borrow) {
                        $activityType = 'asset_borrow_pending';
                        $activityText = 'mengajukan peminjaman aset';
                        $color = 'yellow';

                        if ($borrow->status === 'approved') {
                            $activityType = 'asset_borrow_approved';
                            $activityText = 'peminjaman aset disetujui';
                            $color = 'green';
                        } elseif ($borrow->status === 'borrowed') {
                            $activityType = 'asset_borrowed';
                            $activityText = 'meminjam aset';
                            $color = 'blue';
                        } elseif ($borrow->status === 'returned') {
                            $activityType = 'asset_returned';
                            $activityText = 'mengembalikan aset';
                            $color = 'purple';
                        } elseif ($borrow->status === 'rejected') {
                            $activityType = 'asset_borrow_rejected';
                            $activityText = 'peminjaman aset ditolak';
                            $color = 'red';
                        } elseif ($borrow->status === 'overdue') {
                            $activityType = 'asset_overdue';
                            $activityText = 'terlambat mengembalikan aset';
                            $color = 'red';
                        }

                        return [
                            'type' => $activityType,
                            'user' => $borrow->borrower->name ?? 'Unknown',
                            'title' => $borrow->asset->nama ?? 'Aset',
                            'activity_text' => $activityText,
                            'time' => $borrow->created_at->diffForHumans(),
                            'timestamp' => $borrow->created_at,
                            'color' => $color
                        ];
                    });

                // ✅ NEW: Aktivitas Aset
                $recentAssets = Asset::latest()
                    ->limit(6)
                    ->get()
                    ->map(function ($asset) {
                        $isNew = $asset->created_at->diffInHours(Carbon::now()) < 1;
                        
                        return [
                            'type' => $isNew ? 'asset_created' : 'asset_updated',
                            'user' => $asset->penanggung_jawab ?? 'Admin',
                            'title' => $asset->nama,
                            'activity_text' => $isNew ? 'menambahkan aset baru' : 'memperbarui aset',
                            'time' => $asset->created_at->diffForHumans(),
                            'timestamp' => $asset->created_at,
                            'color' => $isNew ? 'indigo' : 'gray'
                        ];
                    });

                // Gabungkan dan urutkan - ambil 10 teratas
                $recentActivities = $recentArchives
                    ->concat($recentDispositions)
                    ->concat($recentAssetBorrows)
                    ->concat($recentAssets)
                    ->sortByDesc('timestamp')
                    ->take(10)
                    ->values();
                
                // Get Latest Archives for display (10 items for auto-scroll)
                $latestArchives = Archive::with(['category'])
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->map(function($archive) {
                        return [
                            'id' => $archive->id,
                            'judul' => $archive->judul,
                            'category' => $archive->category->name ?? 'Tanpa Kategori',
                            'tanggal_surat' => $archive->tanggal_surat->format('d M Y'),
                            'priority' => $archive->priority ?? 'normal'
                        ];
                    });
                
                // Get Category Distribution
                $categoryDistribution = Archive::select('category_id', DB::raw('count(*) as total'))
                    ->with('category')
                    ->groupBy('category_id')
                    ->orderByDesc('total')
                    ->limit(5)
                    ->get()
                    ->map(function($item) {
                        return [
                            'name' => $item->category ? $item->category->name : 'Tanpa Kategori',
                            'total' => $item->total
                        ];
                    });
                
                return response()->json([
                    'success' => true,
                    'recentActivities' => $recentActivities,
                    'latestArchives' => $latestArchives,
                    'categoryDistribution' => $categoryDistribution
                ]);
            }

            // Legacy support untuk type-specific requests
            switch ($type) {
                case 'stats':
                    return response()->json([
                        'success' => true,
                        'totalArchives' => Archive::count(),
                        'currentMonthArchives' => Archive::whereMonth('created_at', Carbon::now()->month)->count(),
                        'activeUsers' => User::where('is_active', true)->count(),
                        'pendingDispositions' => Disposition::whereIn('status', ['pending', 'in_progress'])->count(),
                    ]);

                case 'activities':
                    // Return activities with asset activities included
                    $recentArchives = Archive::with('uploader')
                        ->latest()
                        ->limit(10)
                        ->get()
                        ->map(function ($archive) {
                            return [
                                'type' => 'upload',
                                'user' => $archive->uploader->name ?? 'Unknown',
                                'title' => $archive->judul,
                                'time' => $archive->created_at->diffForHumans(),
                                'timestamp' => $archive->created_at,
                                'color' => 'blue'
                            ];
                        });

                    return response()->json([
                        'success' => true,
                        'data' => $recentArchives
                    ]);

                case 'chart':
                    $monthlyTrend = [];
                    for ($i = 5; $i >= 0; $i--) {
                        $date = Carbon::now()->subMonths($i);
                        $count = Archive::whereYear('created_at', $date->year)
                            ->whereMonth('created_at', $date->month)
                            ->count();
                        
                        $monthlyTrend[] = [
                            'month' => $date->format('M'),
                            'count' => $count
                        ];
                    }

                    return response()->json([
                        'success' => true,
                        'data' => $monthlyTrend
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid type parameter'
                    ], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Error in getData: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Get chart data based on period filter
     */
    public function getChartData(Request $request)
    {
        try {
            $period = $request->get('period', '6month');
            $customStart = $request->get('start_date');
            $customEnd = $request->get('end_date');
            
            $chartData = [];
            $categories = [];
            
            switch ($period) {
                case '1month':
                    // Data harian untuk 1 bulan terakhir (30 hari)
                    for ($i = 29; $i >= 0; $i--) {
                        $date = Carbon::now()->subDays($i);
                        $count = Archive::whereDate('created_at', $date->format('Y-m-d'))->count();
                        
                        $chartData[] = $count;
                        $categories[] = $date->format('d M');
                    }
                    break;
                    
                case '3month':
                    // Data mingguan untuk 3 bulan terakhir (~12 minggu)
                    for ($i = 11; $i >= 0; $i--) {
                        $startWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                        $endWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                        
                        $count = Archive::whereBetween('created_at', [$startWeek, $endWeek])->count();
                        
                        $chartData[] = $count;
                        $categories[] = $startWeek->format('d M');
                    }
                    break;
                    
                case '6month':
                    // Data bulanan untuk 6 bulan terakhir
                    for ($i = 5; $i >= 0; $i--) {
                        $date = Carbon::now()->subMonths($i);
                        $count = Archive::whereYear('created_at', $date->year)
                            ->whereMonth('created_at', $date->month)
                            ->count();
                        
                        $chartData[] = $count;
                        $categories[] = $date->format('M Y');
                    }
                    break;
                    
                case '1year':
                    // Data bulanan untuk 12 bulan terakhir
                    for ($i = 11; $i >= 0; $i--) {
                        $date = Carbon::now()->subMonths($i);
                        $count = Archive::whereYear('created_at', $date->year)
                            ->whereMonth('created_at', $date->month)
                            ->count();
                        
                        $chartData[] = $count;
                        $categories[] = $date->format('M Y');
                    }
                    break;
                    
                case 'custom':
                    if ($customStart && $customEnd) {
                        $start = Carbon::parse($customStart);
                        $end = Carbon::parse($customEnd);
                        $diffInDays = $start->diffInDays($end);
                        
                        if ($diffInDays <= 31) {
                            // Jika range <= 31 hari, tampilkan per hari
                            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                                $count = Archive::whereDate('created_at', $date->format('Y-m-d'))->count();
                                $chartData[] = $count;
                                $categories[] = $date->format('d M');
                            }
                        } elseif ($diffInDays <= 90) {
                            // Jika range <= 90 hari, tampilkan per minggu
                            $currentWeekStart = $start->copy()->startOfWeek();
                            while ($currentWeekStart->lte($end)) {
                                $weekEnd = $currentWeekStart->copy()->endOfWeek();
                                if ($weekEnd->gt($end)) {
                                    $weekEnd = $end->copy();
                                }
                                
                                $count = Archive::whereBetween('created_at', [$currentWeekStart, $weekEnd])->count();
                                $chartData[] = $count;
                                $categories[] = $currentWeekStart->format('d M');
                                
                                $currentWeekStart->addWeek();
                            }
                        } else {
                            // Jika range > 90 hari, tampilkan per bulan
                            $currentMonth = $start->copy()->startOfMonth();
                            while ($currentMonth->lte($end)) {
                                $monthEnd = $currentMonth->copy()->endOfMonth();
                                if ($monthEnd->gt($end)) {
                                    $monthEnd = $end->copy();
                                }
                                
                                $count = Archive::whereBetween('created_at', [$currentMonth, $monthEnd])->count();
                                $chartData[] = $count;
                                $categories[] = $currentMonth->format('M Y');
                                
                                $currentMonth->addMonth();
                            }
                        }
                    }
                    break;
                    
                default:
                    // Default: 6 bulan terakhir
                    for ($i = 5; $i >= 0; $i--) {
                        $date = Carbon::now()->subMonths($i);
                        $count = Archive::whereYear('created_at', $date->year)
                            ->whereMonth('created_at', $date->month)
                            ->count();
                        
                        $chartData[] = $count;
                        $categories[] = $date->format('M Y');
                    }
            }
            
            // Hitung statistik tambahan
            $total = array_sum($chartData);
            $average = $total > 0 && count($chartData) > 0 ? round($total / count($chartData), 1) : 0;
            $max = count($chartData) > 0 ? max($chartData) : 0;
            $min = count($chartData) > 0 ? min($chartData) : 0;
            
            return response()->json([
                'success' => true,
                'data' => $chartData,
                'categories' => $categories,
                'stats' => [
                    'total' => $total,
                    'average' => $average,
                    'max' => $max,
                    'min' => $min
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getChartData: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data chart',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}