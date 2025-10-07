@extends('staff.layouts.app')

@section('title', 'Dashboard Staff')

@section('content')
<div class="p-6">
    
    {{-- MAIN HEADER DASHBOARD --}}
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 p-6 rounded-2xl text-white mb-6 shadow-lg">
        <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-yellow-100">GANDARIA Arsip Digital Diskominfo Kabupaten Barito Kuala</p>
    </div>

    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Arsip --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-blue-500">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Total Arsip</p>
                    <h3 class="text-4xl font-bold text-gray-800">2,847</h3>
                    <p class="text-xs text-green-600 mt-2">+12% dari bulan lalu</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Arsip Bulan Ini --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-purple-500">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Arsip Bulan Ini</p>
                    <h3 class="text-4xl font-bold text-gray-800">184</h3>
                    <p class="text-xs text-green-600 mt-2">+8% dari bulan lalu</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pengguna Aktif --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-green-500">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Pengguna Aktif</p>
                    <h3 class="text-4xl font-bold text-gray-800">45</h3>
                    <p class="text-xs text-green-600 mt-2">+3% dari bulan lalu</p>
                </div>
                <div class="bg-green-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Disposisi Pending --}}
        <div class="stat-card bg-white p-6 rounded-2xl shadow-md border-l-4 border-red-500">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-medium mb-2">Disposisi Pending</p>
                    <h3 class="text-4xl font-bold text-gray-800">12</h3>
                    <p class="text-xs text-red-600 mt-2">-5% dari bulan lalu</p>
                </div>
                <div class="bg-red-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- CHART: Tren Pengarsipan --}}
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Tren Pengarsipan Bulanan</h3>
                    <select class="text-sm border border-gray-300 rounded-lg px-3 py-2">
                        <option>6 Bulan Terakhir</option>
                        <option>12 Bulan Terakhir</option>
                    </select>
                </div>
                <div style="position: relative; height: 300px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            {{-- ARSIP TERBARU --}}
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Arsip Terbaru</h3>
                    <a href="{{ route('staff.arsip.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat Semua →</a>
                </div>
                <div class="space-y-3">
                    <div class="p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-gray-50 transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-800">Surat Keputusan Bupati No. 001/2024</p>
                                <p class="text-xs text-gray-500 mt-1">Surat Keputusan • 2024-01-15</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="text-xs font-medium text-red-700 bg-red-100 px-3 py-1 rounded-full">Tinggi</span>
                                <span class="text-xs font-medium text-green-700 bg-green-100 px-3 py-1 rounded-full">Aktif</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-gray-50 transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-800">Laporan Keuangan Q4 2023</p>
                                <p class="text-xs text-gray-500 mt-1">Laporan • 2024-01-14</p>
                            </div>
                            <div class="flex gap-2">
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
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Aktivitas Terkini</h3>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <p class="text-sm font-medium text-gray-800">Diah mengunggah arsip baru</p>
                        <p class="text-sm text-blue-600 font-medium mt-1">Surat Edaran COVID-19</p>
                        <p class="text-xs text-gray-500 mt-1">5 menit lalu</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-4 py-2">
                        <p class="text-sm font-medium text-gray-800">Aisyah memberikan disposisi</p>
                        <p class="text-sm text-green-600 font-medium mt-1">Proposal Anggaran 2024</p>
                        <p class="text-xs text-gray-500 mt-1">1 jam lalu</p>
                    </div>
                </div>
            </div>

            {{-- CHART: Distribusi Kategori --}}
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Distribusi Kategori Arsip</h3>
                <div style="position: relative; height: 250px;">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>

            {{-- AKSI CEPAT --}}
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Aksi Cepat</h3>
                <div class="space-y-3">
                    <button class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-xl flex items-center justify-center font-semibold shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                        </svg>
                        Upload Arsip Baru
                    </button>
                    <button class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-xl flex items-center justify-center font-semibold shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"/>
                        </svg>
                        Buat Disposisi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Bar Chart
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
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Doughnut Chart
    const doughnutCtx = document.getElementById('doughnutChart');
    if (doughnutCtx) {
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Surat Keputusan', 'Laporan Keuangan', 'Dokumen Proyek', 'Lain-lain'],
                datasets: [{
                    data: [300, 150, 100, 80],
                    backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(245, 158, 11, 0.8)', 'rgba(139, 92, 246, 0.8)', 'rgba(107, 114, 128, 0.8)'],
                    borderColor: ['#fff', '#fff', '#fff', '#fff'],
                    borderWidth: 3,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 15, font: { size: 11 } } }
                }
            }
        });
    }
</script>
@endpush