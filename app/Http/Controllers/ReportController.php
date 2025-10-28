<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Disposition;
use App\Models\User;
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

        // Date range filter
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        // Parse dates
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

        // User Statistics (Admin only)
        $userStats = [];
        if ($role === 'admin') {
            $userStats = [
                'total' => User::count(),
                'admin' => User::where('role', 'admin')->count(),
                'staff' => User::where('role', 'staff')->count(),
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

    /**
     * Archive report
     */
    public function arsip(Request $request)
    {
        $role = Auth::user()->role;
        $user = Auth::user();

        $query = Archive::with(['category', 'uploader']);

        // Date filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('archives.created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Unit filter
        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        if ($role === 'staff') {
            $query->where('user_id', $user->id);
        }

        $archives = $query->orderBy('archives.created_at', 'desc')->paginate(20);

        return view("{$role}.laporan.arsip", compact('archives'));
    }

    /**
     * Disposition report
     */
    public function disposisi(Request $request)
    {
        $role = Auth::user()->role;
        $user = Auth::user();

        $query = Disposition::with(['archive', 'fromUser', 'toUser']);

        // Date filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('dispositions.created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($role === 'staff') {
            $query->where('to_user_id', $user->id);
        }

        $dispositions = $query->orderBy('dispositions.created_at', 'desc')->paginate(20);

        return view("{$role}.laporan.disposisi", compact('dispositions'));
    }

    /**
     * User activity report (Admin only)
     */
    public function user(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get users with manual count (avoid withCount issue)
        $users = User::orderBy('name')->get()->map(function($user) {
            return (object)[
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'unit' => $user->unit,
                'archives_count' => Archive::where('user_id', $user->id)->count(),
                'sent_dispositions_count' => Disposition::where('from_user_id', $user->id)->count(),
                'received_dispositions_count' => Disposition::where('to_user_id', $user->id)->count(),
            ];
        });

        // Paginate manually
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

        return view('admin.laporan.user', ['users' => $paginator]);
    }

    /**
     * Periode statistics report (Monthly & Annual)
     */
    public function periode(Request $request)
    {
        $role = Auth::user()->role;
        
        $type = $request->input('type', 'monthly'); // monthly or yearly
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        
        if ($type === 'monthly') {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        } else {
            $startDate = Carbon::create($year, 1, 1)->startOfYear();
            $endDate = Carbon::create($year, 12, 31)->endOfYear();
        }

        // Archive stats
        $archiveStats = [
            'total' => Archive::whereBetween('archives.created_at', [$startDate, $endDate])->count(),
            'by_category' => Archive::whereBetween('archives.created_at', [$startDate, $endDate])
                ->join('categories', 'archives.category_id', '=', 'categories.id')
                ->selectRaw('categories.name as category, COUNT(*) as total')
                ->groupBy('categories.name')
                ->get(),
        ];

        // Disposition stats
        $dispositionStats = [
            'total' => Disposition::whereBetween('dispositions.created_at', [$startDate, $endDate])->count(),
            'pending' => Disposition::whereBetween('dispositions.created_at', [$startDate, $endDate])->where('status', 'pending')->count(),
            'in_progress' => Disposition::whereBetween('dispositions.created_at', [$startDate, $endDate])->where('status', 'in_progress')->count(),
            'completed' => Disposition::whereBetween('dispositions.created_at', [$startDate, $endDate])->where('status', 'completed')->count(),
        ];

        // Chart data based on type
        if ($type === 'monthly') {
            // Per day in month
            $chartData = Archive::whereBetween('archives.created_at', [$startDate, $endDate])
                ->selectRaw('DAY(archives.created_at) as period, COUNT(*) as total')
                ->groupBy('period')
                ->orderBy('period')
                ->pluck('total', 'period');
        } else {
            // Per month in year
            $chartData = Archive::whereBetween('archives.created_at', [$startDate, $endDate])
                ->selectRaw('MONTH(archives.created_at) as period, COUNT(*) as total')
                ->groupBy('period')
                ->orderBy('period')
                ->pluck('total', 'period');
        }

        // Compare with previous period
        if ($type === 'monthly') {
            $prevStartDate = Carbon::create($year, $month, 1)->subMonth()->startOfMonth();
            $prevEndDate = Carbon::create($year, $month, 1)->subMonth()->endOfMonth();
        } else {
            $prevStartDate = Carbon::create($year - 1, 1, 1)->startOfYear();
            $prevEndDate = Carbon::create($year - 1, 12, 31)->endOfYear();
        }

        $comparison = [
            'archives' => [
                'current' => $archiveStats['total'],
                'previous' => Archive::whereBetween('archives.created_at', [$prevStartDate, $prevEndDate])->count(),
            ],
            'dispositions' => [
                'current' => $dispositionStats['total'],
                'previous' => Disposition::whereBetween('dispositions.created_at', [$prevStartDate, $prevEndDate])->count(),
            ],
        ];

        // Calculate percentage
        foreach ($comparison as $key => $data) {
            if ($data['previous'] > 0) {
                $comparison[$key]['percentage'] = round((($data['current'] - $data['previous']) / $data['previous']) * 100, 1);
            } else {
                $comparison[$key]['percentage'] = 0;
            }
        }

        return view("{$role}.laporan.periode", compact(
            'archiveStats',
            'dispositionStats',
            'chartData',
            'comparison',
            'type',
            'month',
            'year',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Unit productivity report
     */
    public function unitKerja(Request $request)
    {
        $role = Auth::user()->role;
        
        // Date range
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Get all units from users (using 'unit' field)
        $unitsList = User::whereNotNull('unit')
            ->where('unit', '!=', '')
            ->distinct()
            ->pluck('unit');

        $unitData = [];

        foreach ($unitsList as $unitName) {
            // Get users in this unit
            $userIds = User::where('unit', $unitName)->pluck('id');

            // Archives created by unit (using 'user_id' field)
            $totalArchives = Archive::whereIn('user_id', $userIds)
                ->whereBetween('archives.created_at', [$startDate, $endDate])
                ->count();

            // Dispositions received by unit
            $totalDispositions = Disposition::whereIn('to_user_id', $userIds)
                ->whereBetween('dispositions.created_at', [$startDate, $endDate])
                ->count();

            // Dispositions completed by unit
            $completedDispositions = Disposition::whereIn('to_user_id', $userIds)
                ->where('status', 'completed')
                ->whereBetween('dispositions.created_at', [$startDate, $endDate])
                ->count();

            // Calculate completion rate
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

        // Sort by completion rate or total archives
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

        return view("{$role}.laporan.unit-kerja", compact('units', 'startDate', 'endDate'));
    }

    /**
     * PRINT PDF - Preview di browser untuk dicetak
     */
    public function printPdf(Request $request)
    {
        $type = $request->input('type', 'summary');
        $data = $this->getExportData($type, $request);

        $pdf = Pdf::loadView("reports.pdf.{$type}", $data);
        
        // stream() = Tampil di browser (untuk print preview)
        return $pdf->stream("laporan_{$type}_" . date('Y-m-d') . ".pdf");
    }

    /**
     * DOWNLOAD PDF - Download langsung
     */
    public function exportPdf(Request $request)
    {
        $type = $request->input('type', 'summary');
        $data = $this->getExportData($type, $request);

        $pdf = Pdf::loadView("reports.pdf.{$type}", $data);
        
        // download() = Download file langsung
        return $pdf->download("laporan_{$type}_" . date('Y-m-d') . ".pdf");
    }

    /**
     * Export report to Excel
     */
    public function exportExcel(Request $request)
    {
        $type = $request->input('type', 'arsip');
        
        // Will be implemented with Laravel Excel package
        return back()->with('info', 'Fitur export Excel akan segera tersedia');
    }

    /**
     * Get chart data
     */
    private function getChartData($startDate, $endDate, $role)
    {
        // Archives per month
        $archivesPerMonth = Archive::whereBetween('archives.created_at', [$startDate, $endDate])
            ->selectRaw('MONTH(archives.created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Dispositions by status
        $dispositionsByStatus = Disposition::whereBetween('dispositions.created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Archives by category
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

    /**
     * Get data for export
     */
    private function getExportData($type, $request)
    {
        $data = [];

        switch ($type) {
            case 'summary':
                $data = [
                    'archives' => Archive::count(),
                    'dispositions' => Disposition::count(),
                    'users' => User::count(),
                ];
                break;

            case 'arsip':
                $query = Archive::with(['category', 'uploader']);
                
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween('archives.created_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                }
                
                if ($request->filled('category_id')) {
                    $query->where('category_id', $request->category_id);
                }
                
                $data['archives'] = $query->orderBy('archives.created_at', 'desc')->get();
                break;

            case 'disposisi':
                $query = Disposition::with(['archive', 'fromUser', 'toUser']);
                
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween('dispositions.created_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                }
                
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                
                if ($request->filled('priority')) {
                    $query->where('priority', $request->priority);
                }
                
                $data['dispositions'] = $query->orderBy('dispositions.created_at', 'desc')->get();
                break;

            case 'user':
                $users = User::orderBy('name')->get()->map(function($user) {
                    return (object)[
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'unit' => $user->unit,
                        'archives_count' => Archive::where('user_id', $user->id)->count(),
                        'sent_dispositions_count' => Disposition::where('from_user_id', $user->id)->count(),
                        'received_dispositions_count' => Disposition::where('to_user_id', $user->id)->count(),
                    ];
                });
                
                $data['users'] = $users;
                break;

            case 'periode':
                $type = $request->input('period_type', 'monthly');
                $month = $request->input('month', Carbon::now()->month);
                $year = $request->input('year', Carbon::now()->year);
                
                if ($type === 'monthly') {
                    $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                    $endDate = Carbon::create($year, $month, 1)->endOfMonth();
                } else {
                    $startDate = Carbon::create($year, 1, 1)->startOfYear();
                    $endDate = Carbon::create($year, 12, 31)->endOfYear();
                }

                $data['archives'] = Archive::with('category')
                    ->whereBetween('archives.created_at', [$startDate, $endDate])
                    ->get();
                    
                $data['dispositions'] = Disposition::whereBetween('dispositions.created_at', [$startDate, $endDate])->get();
                $data['period_type'] = $type;
                $data['period_label'] = $type === 'monthly' ? $startDate->format('F Y') : 'Tahun ' . $year;
                $data['start_date'] = $startDate;
                $data['end_date'] = $endDate;
                
                // Add comparison
                if ($type === 'monthly') {
                    $prevStart = Carbon::create($year, $month, 1)->subMonth()->startOfMonth();
                    $prevEnd = Carbon::create($year, $month, 1)->subMonth()->endOfMonth();
                } else {
                    $prevStart = Carbon::create($year - 1, 1, 1)->startOfYear();
                    $prevEnd = Carbon::create($year - 1, 12, 31)->endOfYear();
                }
                
                $currentCount = count($data['archives']);
                $previousCount = Archive::whereBetween('archives.created_at', [$prevStart, $prevEnd])->count();
                
                $data['comparison'] = [
                    'current' => $currentCount,
                    'previous' => $previousCount,
                    'percentage' => $previousCount > 0 
                        ? round((($currentCount - $previousCount) / $previousCount) * 100, 1) 
                        : 0
                ];
                break;

            case 'unit':
                $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
                $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
                
                $startDate = Carbon::parse($startDate)->startOfDay();
                $endDate = Carbon::parse($endDate)->endOfDay();

                $unitsList = User::whereNotNull('unit')
                    ->where('unit', '!=', '')
                    ->distinct()
                    ->pluck('unit');

                $unitData = [];
                foreach ($unitsList as $unitName) {
                    $userIds = User::where('unit', $unitName)->pluck('id');

                    $totalArchives = Archive::whereIn('user_id', $userIds)
                        ->whereBetween('archives.created_at', [$startDate, $endDate])
                        ->count();

                    $totalDispositions = Disposition::whereIn('to_user_id', $userIds)
                        ->whereBetween('dispositions.created_at', [$startDate, $endDate])
                        ->count();

                    $completedDispositions = Disposition::whereIn('to_user_id', $userIds)
                        ->where('status', 'completed')
                        ->whereBetween('dispositions.created_at', [$startDate, $endDate])
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

                // Sort by completion rate
                usort($unitData, function($a, $b) {
                    return $b['completion_rate'] <=> $a['completion_rate'];
                });

                $data['units'] = $unitData;
                $data['start_date'] = $startDate;
                $data['end_date'] = $endDate;
                break;
        }

        return $data;
    }
}