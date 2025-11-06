<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\Disposition;
use App\Models\User;
use App\Models\Asset;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ✅ Dashboard Controller untuk Pimpinan
 * Menampilkan data monitoring, statistik, dan laporan eksekutif
 */
class DashboardController extends Controller
{
    public function index()
    {
        // ============================================
        // 1. EXECUTIVE SUMMARY CARDS
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

        // Total Aset
        $totalAssets = 0;
        if (Schema::hasTable('assets')) {
            try {
                $totalAssets = Asset::count();
            } catch (\Exception $e) {
                $totalAssets = 0;
            }
        }

        // Disposisi Aktif (Pending + In Progress)
        $activeDispositions = Disposition::whereIn('status', ['pending', 'in_progress'])->count();
        
        // Disposisi Selesai Bulan Ini
        $completedDispositionsThisMonth = Disposition::where('status', 'completed')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->count();

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
        // 3. DISTRIBUSI KATEGORI ARSIP (Top 5)
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
                    'color' => $item->category ? ($item->category->color ?? '#6B7280') : '#6B7280'
                ];
            });

        // ============================================
        // 4. STATISTIK DISPOSISI
        // ============================================
        $dispositionStats = [
            'total' => Disposition::count(),
            'pending' => Disposition::where('status', 'pending')->count(),
            'in_progress' => Disposition::where('status', 'in_progress')->count(),
            'completed' => Disposition::where('status', 'completed')->count(),
            'cancelled' => Disposition::where('status', 'cancelled')->count(),
        ];

        // ============================================
        // 5. TOP 5 UNIT KERJA (berdasarkan arsip)
        // ✅ FIXED: Qualify column dengan table name
        // ============================================
        $topUnits = User::select('users.unit', DB::raw('COUNT(archives.id) as total_archives'))
            ->leftJoin('archives', 'users.id', '=', 'archives.user_id')
            ->whereNotNull('users.unit')
            ->where('users.unit', '!=', '')
            ->groupBy('users.unit')
            ->orderByDesc('total_archives')
            ->limit(5)
            ->get();

        // ============================================
        // 6. ARSIP TERBARU (10 Terakhir)
        // ============================================
        $latestArchives = Archive::with(['category', 'uploader'])
            ->latest()
            ->limit(10)
            ->get();

        // ============================================
        // 7. DISPOSISI MENDESAK (Deadline < 7 hari)
        // ============================================
        $urgentDispositions = Disposition::with(['archive', 'toUser'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->where('deadline', '<=', Carbon::now()->addDays(7))
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        // ============================================
        // 8. PERFORMA STAFF (Top 5 Contributors)
        // ✅ FIXED: Qualify all columns dengan table name
        // ============================================
        $topContributors = User::select(
                'users.id',
                'users.name',
                'users.unit',
                'users.role',
                DB::raw('COUNT(archives.id) as archives_count')
            )
            ->leftJoin('archives', 'users.id', '=', 'archives.user_id')
            ->where('users.role', '!=', 'pimpinan') // Exclude pimpinan
            ->where('users.is_active', true) // Only active users
            ->groupBy('users.id', 'users.name', 'users.unit', 'users.role')
            ->orderByDesc('archives_count')
            ->limit(5)
            ->get();

        // ============================================
        // 9. STATISTIK USER
        // ============================================
        $userStats = [
            'total' => User::where('is_active', true)->count(),
            'admin' => User::where('role', 'admin')->where('is_active', true)->count(),
            'staff' => User::where('role', 'staff')->where('is_active', true)->count(),
            'pimpinan' => User::where('role', 'pimpinan')->where('is_active', true)->count(),
        ];

        // ============================================
        // RETURN TO VIEW
        // ============================================
        return view('pimpinan.dashboard', compact(
            // Executive Summary
            'totalArchives',
            'archivesGrowth',
            'currentMonthArchives',
            'totalAssets',
            'activeDispositions',
            'completedDispositionsThisMonth',
            
            // Charts & Analytics
            'monthlyTrend',
            'categoryDistribution',
            'dispositionStats',
            'topUnits',
            
            // Recent Data
            'latestArchives',
            'urgentDispositions',
            'topContributors',
            'userStats'
        ));
    }

    /**
     * Get dashboard data via AJAX (untuk real-time updates)
     */
    public function getData(Request $request)
    {
        try {
            $type = $request->get('type', 'all');

            if ($type === 'all' || !$request->has('type')) {
                // Latest Archives
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
                
                // Category Distribution
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
                        'activeDispositions' => Disposition::whereIn('status', ['pending', 'in_progress'])->count(),
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid type parameter'
                    ], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Error in Pimpinan getData: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data dashboard',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get chart data based on period filter
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
                    
                case '6month':
                default:
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
                        } else {
                            // Jika range > 31 hari, tampilkan per bulan
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
            Log::error('Error in Pimpinan getChartData: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data chart',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}