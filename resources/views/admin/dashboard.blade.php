@extends('admin.layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="p-6">
    
    {{-- MAIN HEADER DASHBOARD --}}
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 p-6 rounded-2xl text-white mb-6 shadow-lg card-animate">
        <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}! 👋</h1>
        <p class="text-yellow-100">GANDARIA Arsip Digital Diskominfo Kabupaten Barito Kuala</p>
    </div>

    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card 1: Total Arsip --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-blue-500 card-animate card-animate-delay-1">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Total Arsip</p>
                    <h3 class="text-4xl font-bold text-gray-800 stat-number" data-target="2847">0</h3>
                    <p class="text-xs text-green-600 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                        </svg>
                        +12% dari bulan lalu
                    </p>
                </div>
                <div class="stat-icon bg-blue-100 p-3 rounded-xl flex-shrink-0 ml-3">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full progress-bar" style="width: 0%" data-progress="75"></div>
            </div>
        </div>

        {{-- Card 2: Arsip Bulan Ini --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-purple-500 card-animate card-animate-delay-2">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Arsip Bulan Ini</p>
                    <h3 class="text-4xl font-bold text-gray-800 stat-number" data-target="184">0</h3>
                    <p class="text-xs text-green-600 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                        </svg>
                        +8% dari bulan lalu
                    </p>
                </div>
                <div class="stat-icon bg-purple-100 p-3 rounded-xl flex-shrink-0 ml-3">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-purple-600 h-2 rounded-full progress-bar" style="width: 0%" data-progress="60"></div>
            </div>
        </div>

        {{-- Card 3: Pengguna Aktif --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-green-500 card-animate card-animate-delay-3">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Pengguna Aktif</p>
                    <h3 class="text-4xl font-bold text-gray-800 stat-number" data-target="45">0</h3>
                    <p class="text-xs text-green-600 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                        </svg>
                        +3% dari bulan lalu
                    </p>
                </div>
                <div class="stat-icon bg-green-100 p-3 rounded-xl flex-shrink-0 ml-3">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full progress-bar" style="width: 0%" data-progress="85"></div>
            </div>
        </div>

        {{-- Card 4: Disposisi Pending --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-red-500 card-animate card-animate-delay-4">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Disposisi Pending</p>
                    <h3 class="text-4xl font-bold text-gray-800 stat-number" data-target="12">0</h3>
                    <p class="text-xs text-red-600 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z"/>
                        </svg>
                        -5% dari bulan lalu
                    </p>
                </div>
                <div class="stat-icon bg-red-100 p-3 rounded-xl flex-shrink-0 ml-3">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-red-600 h-2 rounded-full progress-bar" style="width: 0%" data-progress="30"></div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- CHART: Tren Pengarsipan --}}
            <div class="bg-white p-6 rounded-2xl shadow-md card-animate" style="animation-delay: 0.5s;">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Tren Pengarsipan Bulanan</h3>
                    <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>6 Bulan Terakhir</option>
                        <option>12 Bulan Terakhir</option>
                        <option>Tahun Ini</option>
                    </select>
                </div>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            {{-- ARSIP TERBARU --}}
            <div class="bg-white p-6 rounded-2xl shadow-md card-animate" style="animation-delay: 0.6s;">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Arsip Terbaru</h3>
                    <a href="{{ route('admin.arsip.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        Lihat Semua →
                    </a>
                </div>
                <div class="space-y-3">
                    <div class="activity-item p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-gray-50 transition-all">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Surat Keputusan Bupati No. 001/2024</p>
                                <p class="text-xs text-gray-500 mt-1">Surat Keputusan • 2024-01-15</p>
                            </div>
                            <div class="flex gap-2 flex-wrap">
                                <span class="text-xs font-medium text-red-700 bg-red-100 px-3 py-1 rounded-full">Tinggi</span>
                                <span class="text-xs font-medium text-green-700 bg-green-100 px-3 py-1 rounded-full">Aktif</span>
                            </div>
                        </div>
                    </div>
                    <div class="activity-item p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-gray-50 transition-all" style="animation-delay: 0.1s;">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Laporan Keuangan Q4 2023</p>
                                <p class="text-xs text-gray-500 mt-1">Laporan • 2024-01-14</p>
                            </div>
                            <div class="flex gap-2 flex-wrap">
                                <span class="text-xs font-medium text-yellow-700 bg-yellow-100 px-3 py-1 rounded-full">Sedang</span>
                                <span class="text-xs font-medium text-blue-700 bg-blue-100 px-3 py-1 rounded-full">Review</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- AKTIVITAS TERKINI --}}
            <div class="bg-white p-6 rounded-2xl shadow-md card-animate" style="animation-delay: 0.5s;">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Aktivitas Terkini</h3>
                <div class="space-y-4">
                    <div class="activity-item border-l-4 border-blue-500 pl-4 py-2">
                        <p class="text-sm font-medium text-gray-800">Diah mengunggah arsip baru</p>
                        <p class="text-sm text-blue-600 font-medium mt-1">Surat Edaran COVID-19</p>
                        <p class="text-xs text-gray-500 mt-1">5 menit lalu</p>
                    </div>
                    <div class="activity-item border-l-4 border-green-500 pl-4 py-2" style="animation-delay: 0.1s;">
                        <p class="text-sm font-medium text-gray-800">Aisyah memberikan disposisi</p>
                        <p class="text-sm text-green-600 font-medium mt-1">Proposal Anggaran 2024</p>
                        <p class="text-xs text-gray-500 mt-1">1 jam lalu</p>
                    </div>
                </div>
            </div>

            {{-- CHART: Distribusi Kategori --}}
            <div class="bg-white p-6 rounded-2xl shadow-md card-animate" style="animation-delay: 0.6s;">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Distribusi Kategori Arsip</h3>
                <div class="chart-container" style="height: 250px;">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>

            {{-- AKSI CEPAT --}}
            <div class="bg-white p-6 rounded-2xl shadow-md card-animate" style="animation-delay: 0.7s;">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Aksi Cepat</h3>
                <div class="space-y-3">
                    <button class="btn-action w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-xl flex items-center justify-center font-semibold shadow-md hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                            <path d="M9 13h2v5a1 1 0 11-2 0v-5z"/>
                        </svg>
                        Upload Arsip Baru
                    </button>
                    <button class="btn-action w-full bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-xl flex items-center justify-center font-semibold shadow-md hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"/>
                        </svg>
                        Buat Disposisi
                    </button>
                    <button class="btn-action w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-xl flex items-center justify-center font-semibold shadow-md hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Kelola User
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .card-animate {
        animation: fadeInUp 0.6s ease-out;
        opacity: 0;
        animation-fill-mode: forwards;
    }
    
    .card-animate-delay-1 { animation-delay: 0.1s; }
    .card-animate-delay-2 { animation-delay: 0.2s; }
    .card-animate-delay-3 { animation-delay: 0.3s; }
    .card-animate-delay-4 { animation-delay: 0.4s; }
    
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.15) rotate(8deg);
    }
    
    .stat-icon {
        transition: transform 0.3s ease;
    }
    
    .stat-number {
        font-variant-numeric: tabular-nums;
    }
    
    .progress-bar {
        transition: width 1s ease-out;
    }
    
    .activity-item {
        animation: slideInRight 0.5s ease-out;
        transition: all 0.3s ease;
    }
    
    .activity-item:hover {
        transform: translateX(5px);
    }
    
    .btn-action {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-action::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-action:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
    }
    
    .chart-container {
        position: relative;
        transition: all 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter Animation
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;

            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    element.textContent = Math.floor(current).toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target.toLocaleString();
                }
            };
            updateCounter();
        }

        // Progress Bar Animation
        function animateProgressBar(element) {
            const target = parseInt(element.getAttribute('data-progress'));
            setTimeout(() => {
                element.style.width = target + '%';
            }, 300);
        }

        // Initialize animations
        document.querySelectorAll('.stat-number').forEach(animateCounter);
        document.querySelectorAll('.progress-bar').forEach(animateProgressBar);

        // Initialize Charts
        const barCtx = document.getElementById('barChart');
        if (barCtx) {
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Dokumen Diunggah',
                        data: [12, 19, 15, 22, 18, 25],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(37, 99, 235)',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            borderRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        const doughnutCtx = document.getElementById('doughnutChart');
        if (doughnutCtx) {
            new Chart(doughnutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Surat Keputusan', 'Laporan Keuangan', 'Dokumen Proyek', 'Lain-lain'],
                    datasets: [{
                        data: [300, 150, 100, 80],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(107, 114, 128, 0.8)'
                        ],
                        borderColor: ['#fff', '#fff', '#fff', '#fff'],
                        borderWidth: 3,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: { size: 11 },
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush