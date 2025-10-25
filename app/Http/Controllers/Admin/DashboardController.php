<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\Disposition;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        // 4. AKTIVITAS TERKINI (10 Terakhir)
        // ============================================
        $recentActivities = collect();

        // Arsip terbaru
        $recentArchives = Archive::with('uploader')
            ->latest()
            ->limit(5)
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

        // Disposisi terbaru
        $recentDispositions = Disposition::with(['fromUser', 'archive'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($disposition) {
                return [
                    'type' => 'disposition',
                    'user' => $disposition->fromUser->name ?? 'Unknown',
                    'title' => $disposition->archive->judul ?? $disposition->subject,
                    'time' => $disposition->created_at->diffForHumans(),
                    'timestamp' => $disposition->created_at,
                    'color' => 'green'
                ];
            });

        // Gabungkan dan urutkan
        $recentActivities = $recentArchives->concat($recentDispositions)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();

        // ============================================
        // 5. ARSIP TERBARU (5 Terakhir)
        // ============================================
        $latestArchives = Archive::with(['category', 'uploader'])
            ->latest()
            ->limit(5)
            ->get();

        // ============================================
        // 6. DISPOSISI MENDESAK (Deadline < 7 hari)
        // ============================================
        $urgentDispositions = Disposition::with(['archive', 'toUser'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->where('deadline', '<=', Carbon::now()->addDays(7))
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        // ============================================
        // 7. TOP CONTRIBUTORS (User dengan arsip terbanyak)
        // ✅ FIXED: Sesuai MySQL strict mode requirements
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
            'topContributors'
        ));
    }

    /**
     * Get dashboard data via AJAX (untuk real-time updates)
     */
    public function getData(Request $request)
    {
        $type = $request->get('type', 'stats');

        switch ($type) {
            case 'stats':
                return response()->json([
                    'totalArchives' => Archive::count(),
                    'currentMonthArchives' => Archive::whereMonth('created_at', Carbon::now()->month)->count(),
                    'activeUsers' => User::where('is_active', true)->count(),
                    'pendingDispositions' => Disposition::whereIn('status', ['pending', 'in_progress'])->count(),
                ]);

            case 'activities':
                $recentArchives = Archive::with('uploader')
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(function ($archive) {
                        return [
                            'type' => 'upload',
                            'user' => $archive->uploader->name ?? 'Unknown',
                            'title' => $archive->judul,
                            'time' => $archive->created_at->diffForHumans(),
                            'timestamp' => $archive->created_at,
                        ];
                    });

                return response()->json($recentArchives);

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

                return response()->json($monthlyTrend);

            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }
    }

    /**
     * ✅ NEW: Get chart data based on period filter
     */
    public function getChartData(Request $request)
    {
        $period = $request->get('period', '1month');
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
                    $categories[] = $startWeek->format('d M') . ' - ' . $endWeek->format('d M');
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
                            $categories[] = $currentWeekStart->format('d M') . ' - ' . $weekEnd->format('d M');
                            
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
        $average = $total > 0 ? round($total / count($chartData), 1) : 0;
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
    }
}