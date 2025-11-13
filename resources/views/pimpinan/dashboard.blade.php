@extends('pimpinan.layouts.app')

@section('title', 'Dashboard Pimpinan')

@push('styles')
<style>
    /* Modern Card Styles */
    .stat-card {
        border-radius: 16px;
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.12) !important;
    }
    
    .stat-card .icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }
    
    .stat-card .stat-number {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin: 12px 0 8px;
    }
    
    .stat-card .stat-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .stat-card .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 8px;
    }
    
    /* Chart Card */
    .chart-card {
        border-radius: 16px;
        border: none;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .chart-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px 24px;
        border-radius: 16px 16px 0 0;
    }
    
    .chart-card.info .card-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
    }
    
    .chart-card.success .card-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .chart-card.warning .card-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    /* List Items */
    .modern-list-item {
        padding: 16px;
        border-radius: 12px;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        margin-bottom: 12px;
    }
    
    .modern-list-item:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateX(4px);
    }
    
    .modern-list-item:last-child {
        margin-bottom: 0;
    }
    
    /* Badge Styles */
    .rank-badge {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
    }
    
    .rank-badge.gold {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
    }
    
    .rank-badge.silver {
        background: linear-gradient(135deg, #d1d5db 0%, #9ca3af 100%);
        color: white;
    }
    
    .rank-badge.bronze {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        color: white;
    }
    
    .rank-badge.default {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
    }
    
    /* Table Styles */
    .modern-table {
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
    }
    
    .modern-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
    
    .modern-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .modern-table tbody tr:hover {
        background: #f8fafc;
    }
    
    /* Button Styles */
    .btn-modern {
        border-radius: 10px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    
    .btn-modern-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-modern-info {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
        color: white;
    }
    
    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 32px;
        color: white;
        margin-bottom: 32px;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
    }
    
    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .page-header .subtitle {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    
    /* Urgent Badge Animation */
    @keyframes pulse-urgent {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
    
    .urgent-badge {
        animation: pulse-urgent 2s ease-in-out infinite;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .stat-card .stat-number {
            font-size: 1.5rem;
        }
        
        .page-header {
            padding: 24px;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    
    <!-- Modern Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Dashboard Pimpinan
                </h1>
                <p class="subtitle mb-0">
                    <i class="far fa-calendar-alt me-2"></i>
                    {{ now()->translatedFormat('l, d F Y') }} • Monitoring & Analytics
                </p>
            </div>
            <button class="btn btn-light btn-modern" onclick="refreshDashboard()">
                <i class="fas fa-sync-alt me-2"></i>
                Refresh Data
            </button>
        </div>
    </div>

    <!-- Executive Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Arsip -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label">Total Arsip</div>
                            <div class="stat-number text-primary">{{ number_format($totalArchives) }}</div>
                            @if($archivesGrowth > 0)
                                <span class="stat-badge bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-arrow-up"></i> {{ $archivesGrowth }}% Bulan ini
                                </span>
                            @elseif($archivesGrowth < 0)
                                <span class="stat-badge bg-danger bg-opacity-10 text-danger">
                                    <i class="fas fa-arrow-down"></i> {{ abs($archivesGrowth) }}%
                                </span>
                            @else
                                <span class="stat-badge bg-secondary bg-opacity-10 text-secondary">
                                    <i class="fas fa-minus"></i> Stabil
                                </span>
                            @endif
                        </div>
                        <div class="icon-wrapper bg-primary bg-opacity-10">
                            <i class="fas fa-file-archive text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Arsip Bulan Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label">Arsip Bulan Ini</div>
                            <div class="stat-number text-info">{{ number_format($currentMonthArchives) }}</div>
                            <span class="stat-badge bg-info bg-opacity-10 text-info">
                                <i class="fas fa-calendar"></i> {{ now()->translatedFormat('F Y') }}
                            </span>
                        </div>
                        <div class="icon-wrapper bg-info bg-opacity-10">
                            <i class="fas fa-calendar-plus text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disposisi Aktif -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label">Disposisi Aktif</div>
                            <div class="stat-number text-warning">{{ number_format($activeDispositions) }}</div>
                            <span class="stat-badge bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-clock"></i> Pending & Progress
                            </span>
                        </div>
                        <div class="icon-wrapper bg-warning bg-opacity-10">
                            <i class="fas fa-tasks text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Aset -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="stat-label">Total Aset</div>
                            <div class="stat-number text-success">{{ number_format($totalAssets) }}</div>
                            <span class="stat-badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-boxes"></i> Inventaris
                            </span>
                        </div>
                        <div class="icon-wrapper bg-success bg-opacity-10">
                            <i class="fas fa-box text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Tren Pengarsipan -->
        <div class="col-xl-8">
            <div class="chart-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h5 class="mb-1 fw-bold">
                                <i class="fas fa-chart-line me-2"></i>
                                Tren Pengarsipan
                            </h5>
                            <small class="opacity-90">Grafik 6 bulan terakhir</small>
                        </div>
                        <select class="form-select form-select-sm w-auto" id="periodFilter" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white;">
                            <option value="6month">6 Bulan</option>
                            <option value="3month">3 Bulan</option>
                            <option value="1month">1 Bulan</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-4">
                    <canvas id="trendChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Kategori Arsip -->
        <div class="col-xl-4">
            <div class="chart-card info">
                <div class="card-header">
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-chart-pie me-2"></i>
                        Kategori Arsip
                    </h5>
                    <small class="opacity-90">Top 5 kategori terbanyak</small>
                </div>
                <div class="card-body p-4">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <!-- Statistik Disposisi -->
        <div class="col-xl-4">
            <div class="chart-card warning h-100">
                <div class="card-header">
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistik Disposisi
                    </h5>
                    <small class="opacity-90">Status disposisi saat ini</small>
                </div>
                <div class="card-body p-4">
                    <div class="modern-list-item bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold"><i class="fas fa-list me-2 text-secondary"></i>Total Disposisi</span>
                            <span class="badge bg-secondary px-3 py-2">{{ $dispositionStats['total'] }}</span>
                        </div>
                    </div>
                    
                    <div class="modern-list-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-medium"><i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>Pending</span>
                            <span class="badge bg-warning px-3 py-2">{{ $dispositionStats['pending'] }}</span>
                        </div>
                    </div>
                    
                    <div class="modern-list-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-medium"><i class="fas fa-circle text-info me-2" style="font-size: 8px;"></i>In Progress</span>
                            <span class="badge bg-info px-3 py-2">{{ $dispositionStats['in_progress'] }}</span>
                        </div>
                    </div>
                    
                    <div class="modern-list-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-medium"><i class="fas fa-circle text-success me-2" style="font-size: 8px;"></i>Completed</span>
                            <span class="badge bg-success px-3 py-2">{{ $dispositionStats['completed'] }}</span>
                        </div>
                    </div>
                    
                    <div class="modern-list-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-medium"><i class="fas fa-circle text-danger me-2" style="font-size: 8px;"></i>Cancelled</span>
                            <span class="badge bg-danger px-3 py-2">{{ $dispositionStats['cancelled'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Unit Kerja -->
        <div class="col-xl-4">
            <div class="chart-card success h-100">
                <div class="card-header">
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-building me-2"></i>
                        Top Unit Kerja
                    </h5>
                    <small class="opacity-90">5 unit terbanyak mengarsip</small>
                </div>
                <div class="card-body p-4">
                    @forelse($topUnits as $index => $unit)
                        <div class="modern-list-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3 flex-grow-1">
                                    <div class="rank-badge {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : 'default')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="fw-medium">{{ Str::limit($unit->unit, 25) }}</span>
                                </div>
                                <span class="badge bg-primary px-3 py-2">{{ $unit->total_archives }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada data unit kerja</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Contributors -->
        <div class="col-xl-4">
            <div class="chart-card h-100">
                <div class="card-header">
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-trophy me-2"></i>
                        Top Contributors
                    </h5>
                    <small class="opacity-90">Kontributor terbaik bulan ini</small>
                </div>
                <div class="card-body p-4">
                    @forelse($topContributors as $index => $contributor)
                        <div class="modern-list-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3 flex-grow-1">
                                    <div class="rank-badge {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : 'default')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ Str::limit($contributor->name, 18) }}</div>
                                        <small class="text-muted">{{ Str::limit($contributor->unit ?? 'N/A', 20) }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-success px-3 py-2">{{ $contributor->archives_count }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada data kontributor</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity - FULL WIDTH ROW -->
    <div class="row g-4">
        <!-- Arsip Terbaru - FULL COL-12 -->
        <div class="col-12">
            <div class="chart-card h-100">
                <div class="card-header">
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-clock me-2"></i>
                        Arsip Terbaru
                    </h5>
                    <small class="opacity-90">10 arsip terakhir ditambahkan</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="modern-table table table-hover mb-0">
                            <thead class="sticky-top bg-white" style="box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <tr>
                                    <th class="fw-semibold text-secondary py-3 px-4" style="font-size: 0.875rem; width: 45%;">Judul Arsip</th>
                                    <th class="fw-semibold text-secondary py-3 px-3 text-center" style="font-size: 0.875rem; width: 15%;">Kategori</th>
                                    <th class="fw-semibold text-secondary py-3 px-3 text-center" style="font-size: 0.875rem; width: 15%;">Unit</th>
                                    <th class="fw-semibold text-secondary py-3 px-3 text-center" style="font-size: 0.875rem; width: 15%;">Tanggal</th>
                                    <th class="fw-semibold text-secondary py-3 px-3 text-center" style="font-size: 0.875rem; width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestArchives as $archive)
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper bg-primary bg-opacity-10 me-3" style="width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <i class="fas fa-file-alt text-primary" style="font-size: 16px;"></i>
                                                </div>
                                                <div style="min-width: 0; flex: 1;">
                                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem;">
                                                        {{ Str::limit($archive->judul, 60) }}
                                                    </div>
                                                    <small class="text-muted" style="font-size: 0.8rem;">{{ $archive->nomor_surat }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" style="font-size: 0.75rem;">
                                                {{ Str::limit($archive->category->name ?? 'Tanpa Kategori', 15) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <span class="text-muted" style="font-size: 0.85rem;">
                                                {{ Str::limit($archive->unit ?? 'N/A', 18) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <small class="text-muted d-block" style="font-size: 0.8rem;">
                                                <i class="far fa-calendar me-1"></i>
                                                {{ $archive->created_at->format('d M Y') }}
                                            </small>
                                            <small class="text-muted" style="font-size: 0.7rem;">
                                                {{ $archive->created_at->format('H:i') }}
                                            </small>
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <a href="{{ route('pimpinan.arsip.show', $archive->id) }}" 
                                               class="btn btn-sm btn-modern-primary" 
                                               style="font-size: 0.8rem; padding: 6px 16px;">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted mb-0">Belum ada arsip terbaru</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(count($latestArchives) > 0)
                <div class="card-footer bg-light text-center py-3 border-top">
                    <a href="{{ route('pimpinan.arsip.index') }}" class="text-primary fw-semibold text-decoration-none" style="font-size: 0.9rem;">
                        <i class="fas fa-arrow-right me-2"></i>Lihat Semua Arsip
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Disposisi Mendesak - Separate Row Below -->
    <div class="row g-4 mt-1">
        <div class="col-xl-6">
            <div class="chart-card h-100" style="background: linear-gradient(135deg, #fff5f5 0%, #ffe4e6 100%); border: 2px solid #fecaca;">
                <div class="card-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Disposisi Mendesak
                    </h5>
                    <small class="opacity-90">Perlu perhatian segera</small>
                </div>
                <div class="card-body p-4">
                    @forelse($urgentDispositions as $disposition)
                        <div class="modern-list-item bg-white urgent-badge">
                            <div class="mb-2">
                                <div class="fw-semibold text-dark mb-1">
                                    <i class="fas fa-file-alt text-danger me-2"></i>
                                    {{ Str::limit($disposition->archive->judul ?? 'N/A', 50) }}
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    {{ Str::limit($disposition->toUser->name ?? 'N/A', 20) }}
                                </small>
                                <small class="badge bg-danger px-2 py-1">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($disposition->deadline)->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3 d-block"></i>
                            <p class="fw-semibold text-success mb-1">Tidak Ada Disposisi Mendesak</p>
                            <p class="text-muted small mb-0">Semua disposisi dalam kendali</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Modern Chart Colors - Kominfo Theme
const chartColors = {
    primary: '#667eea',
    primaryLight: '#a5b4fc',
    info: '#06b6d4',
    infoLight: '#67e8f9',
    success: '#10b981',
    successLight: '#6ee7b7',
    warning: '#f59e0b',
    warningLight: '#fcd34d',
    danger: '#ef4444',
    dangerLight: '#fca5a5',
    purple: '#8b5cf6',
    purpleLight: '#c4b5fd'
};

// Gradient Helper
function createGradient(ctx, color1, color2) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, color1);
    gradient.addColorStop(1, color2);
    return gradient;
}

// Tren Chart
const trendData = @json($monthlyTrend);
const trendCtx = document.getElementById('trendChart').getContext('2d');
const trendGradient = createGradient(trendCtx, 'rgba(102, 126, 234, 0.8)', 'rgba(118, 75, 162, 0.1)');

new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: trendData.map(d => d.month + ' ' + d.year),
        datasets: [{
            label: 'Jumlah Arsip',
            data: trendData.map(d => d.count),
            borderColor: chartColors.primary,
            backgroundColor: trendGradient,
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 6,
            pointHoverRadius: 8,
            pointBackgroundColor: '#fff',
            pointBorderColor: chartColors.primary,
            pointBorderWidth: 3,
            pointHoverBackgroundColor: chartColors.primary,
            pointHoverBorderColor: '#fff',
            pointHoverBorderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        return 'Total: ' + context.parsed.y + ' arsip';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 12
                    },
                    color: '#64748b'
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 12
                    },
                    color: '#64748b'
                },
                grid: {
                    display: false,
                    drawBorder: false
                }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    }
});

// Category Chart
const categoryData = @json($categoryDistribution);
const categoryCtx = document.getElementById('categoryChart').getContext('2d');

new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: categoryData.map(d => d.name),
        datasets: [{
            data: categoryData.map(d => d.total),
            backgroundColor: [
                chartColors.primary,
                chartColors.info,
                chartColors.success,
                chartColors.warning,
                chartColors.danger
            ],
            borderWidth: 4,
            borderColor: '#fff',
            hoverBorderWidth: 6,
            hoverBorderColor: '#fff',
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12,
                        weight: '500'
                    },
                    color: '#475569',
                    usePointStyle: true,
                    pointStyle: 'circle',
                    generateLabels: function(chart) {
                        const data = chart.data;
                        if (data.labels.length && data.datasets.length) {
                            return data.labels.map((label, i) => {
                                const value = data.datasets[0].data[i];
                                const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return {
                                    text: `${label}: ${value} (${percentage}%)`,
                                    fillStyle: data.datasets[0].backgroundColor[i],
                                    hidden: false,
                                    index: i
                                };
                            });
                        }
                        return [];
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} arsip (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Refresh Dashboard Function
function refreshDashboard() {
    Swal.fire({
        title: 'Memperbarui Dashboard...',
        html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i><p>Mohon tunggu sebentar</p></div>',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        location.reload();
    }, 1200);
}

// Period Filter Function
document.getElementById('periodFilter')?.addEventListener('change', function() {
    const period = this.value;
    
    Swal.fire({
        title: 'Memuat Data...',
        html: '<i class="fas fa-spinner fa-spin fa-2x text-primary"></i>',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`{{ route('pimpinan.dashboard.data') }}?period=${period}`)
        .then(response => response.json())
        .then(data => {
            Swal.close();
            console.log('Period changed to:', period, data);
        })
        .catch(error => {
            Swal.close();
            console.error('Error fetching data:', error);
        });
});

// Auto-refresh notification every 60 seconds
setInterval(() => {
    fetch('{{ route("pimpinan.notifikasi.unread-count") }}')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            if (badge && data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.style.display = 'inline-block';
            }
        })
        .catch(error => console.error('Error:', error));
}, 60000);

// Animate numbers on page load
document.addEventListener('DOMContentLoaded', function() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    statNumbers.forEach((element, index) => {
        const finalValue = parseInt(element.textContent.replace(/,/g, ''));
        let currentValue = 0;
        const increment = Math.ceil(finalValue / 50);
        const duration = 1000;
        const stepTime = duration / (finalValue / increment);
        
        setTimeout(() => {
            const counter = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    element.textContent = finalValue.toLocaleString();
                    clearInterval(counter);
                } else {
                    element.textContent = currentValue.toLocaleString();
                }
            }, stepTime);
        }, index * 100);
    });
});

// Responsive Chart Resize
window.addEventListener('resize', function() {
    Chart.instances.forEach(chart => {
        chart.resize();
    });
});
</script>
@endpush