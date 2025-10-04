@extends('admin.layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="p-6">
    
    {{-- MAIN HEADER DASHBOARD --}}
    <div class="bg-yellow-500 p-6 rounded-lg text-white mb-6">
        <h1 class="text-2xl font-bold mb-1">Selamat Datang, {{ Auth::user()->role ?? 'Admin' }}!</h1>
        <p class="text-sm">GANDARIA Arsip Digital Diskominfo Kabupaten Barito Kuala</p>
    </div>

    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-4 gap-6 mb-8">
        {{-- Total Arsip --}}
        <div class="bg-white p-5 rounded-lg shadow-lg flex justify-between items-center border-l-4 border-blue-600">
            <div>
                <p class="text-sm text-gray-500">Total Arsip</p>
                <p class="text-3xl font-bold text-gray-800">2,847</p>
                <p class="text-xs text-green-500 mt-1">+12% dari bulan lalu</p>
            </div>
            <div class="text-gray-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM13 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM13 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"></path></svg>
            </div>
        </div>
        {{-- Arsip Bulan Ini --}}
        <div class="bg-white p-5 rounded-lg shadow-lg flex justify-between items-center border-l-4 border-blue-600">
            <div>
                <p class="text-sm text-gray-500">Arsip Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-800">184</p>
                <p class="text-xs text-green-500 mt-1">+8% dari bulan lalu</p>
            </div>
            <div class="text-gray-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 3a1 1 0 000 2h4a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
            </div>
        </div>
        {{-- Pengguna Aktif --}}
        <div class="bg-white p-5 rounded-lg shadow-lg flex justify-between items-center border-l-4 border-blue-600">
            <div>
                <p class="text-sm text-gray-500">Pengguna Aktif</p>
                <p class="text-3xl font-bold text-gray-800">45</p>
                <p class="text-xs text-green-500 mt-1">+3% dari bulan lalu</p>
            </div>
            <div class="text-gray-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
            </div>
        </div>
        {{-- Disposisi Pending --}}
        <div class="bg-white p-5 rounded-lg shadow-lg flex justify-between items-center border-l-4 border-red-600">
            <div>
                <p class="text-sm text-gray-500">Disposisi Pending</p>
                <p class="text-3xl font-bold text-gray-800">12</p>
                <p class="text-xs text-red-500 mt-1">-5% dari bulan lalu</p>
            </div>
            <div class="text-gray-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.414L11 9.586V6z" clip-rule="evenodd"></path></svg>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT AREA (2 kolom) --}}
    <div class="grid grid-cols-12 gap-6">
        
        {{-- KOLOM KIRI (Arsip Terbaru & Grafik Batang) --}}
        <div class="col-span-8 space-y-6">
            
            {{-- AREA GRAFIK 1 (Bar Chart - KIRI) --}}
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Tren Pengarsipan Bulanan</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            {{-- ARSIP TERBARU --}}
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Arsip Terbaru</h3>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
                </div>
                
                <div class="space-y-4">
                    {{-- Item Arsip 1 --}}
                    <div class="py-3 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-800">Surat Keputusan Bupati No. 001/2024</p>
                            <p class="text-xs text-gray-500">Surat Keputusan • 2024-01-15</p>
                        </div>
                        <div class="flex space-x-2">
                            <span class="text-xs font-medium text-red-700 bg-red-100 px-3 py-1 rounded-full">Tinggi</span>
                            <span class="text-xs font-medium text-green-700 bg-green-100 px-3 py-1 rounded-full">Aktif</span>
                        </div>
                    </div>
                    {{-- Item Arsip 2 --}}
                    <div class="py-3 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-800">Laporan Keuangan Q4 2023</p>
                            <p class="text-xs text-gray-500">Laporan • 2024-01-14</p>
                        </div>
                        <div class="flex space-x-2">
                            <span class="text-xs font-medium text-yellow-700 bg-yellow-100 px-3 py-1 rounded-full">Sedang</span>
                            <span class="text-xs font-medium text-blue-700 bg-blue-100 px-3 py-1 rounded-full">Review</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (Aktivitas Terkini & Grafik Lingkaran) --}}
        <div class="col-span-4 space-y-6">
            
            {{-- AKTIVITAS TERKINI --}}
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Terkini</h3>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-3">
                        <p class="text-sm font-medium text-gray-800">Diah mengunggah arsip baru <span class="font-normal text-blue-600">Surat Edaran COVID-19</span></p>
                        <p class="text-xs text-gray-500">5 menit lalu</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-3">
                        <p class="text-sm font-medium text-gray-800">Aisyah  memberikan disposisi <span class="font-normal text-green-600">Proposal Anggaran 2024</span></p>
                        <p class="text-xs text-gray-500">1 jam lalu</p>
                    </div>
                </div>
            </div>
            
            {{-- AREA GRAFIK 2 (Doughnut Chart - KANAN) --}}
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Distribusi Kategori Arsip</h3>
                <div style="position: relative; height: 250px;">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>

            {{-- AKSI CEPAT --}}
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <button class="w-full text-left p-3 rounded-lg border hover:bg-gray-50 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path></svg>
                        Upload Arsip Baru
                    </button>
                    <button class="w-full text-left p-3 rounded-lg border hover:bg-gray-50 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zm4.356 2.44a1 1 0 10-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 00-1-1h-1a1 1 0 100 2h1a1 1 0 001-1zm-4.356 5.56a1 1 0 101.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM10 18a1 1 0 00-1-1v-1a1 1 0 102 0v1a1 1 0 00-1 1zM5.644 14.356l.707.707a1 1 0 101.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zM2 10a1 1 0 001 1h1a1 1 0 100-2H3a1 1 0 00-1 1zm3.644-4.356l-.707-.707a1 1 0 10-1.414 1.414l.707.707a1 1 0 001.414-1.414z"></path></svg>
                        Buat Disposisi
                    </button>
                    <button class="w-full text-left p-3 rounded-lg border hover:bg-gray-50 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zm-7 9a1 1 0 011-1h6a1 1 0 011 1v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-1z"></path></svg>
                        Kelola User
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // === SCRIPT CHART.JS (Ditempatkan di Stack Scripts) ===
    // Data Placeholder untuk Bar Chart
    const barCtx = document.getElementById('barChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Dokumen Diunggah',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: ['rgba(59, 130, 246, 0.7)'],
                    borderColor: ['rgb(37, 99, 235)'],
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });
    }

    // Data Placeholder untuk Doughnut Chart
    const doughnutCtx = document.getElementById('doughnutChart');
    if (doughnutCtx) {
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Surat Keputusan', 'Laporan Keuangan', 'Dokumen Proyek', 'Lain-lain'],
                datasets: [{
                    data: [300, 50, 100, 40],
                    backgroundColor: [
                        'rgb(59, 130, 246)', 'rgb(245, 158, 11)', 'rgb(139, 92, 246)', 'rgb(107, 114, 128)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    }
</script>
@endpush