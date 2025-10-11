@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Laporan Statistik Periode')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route(Auth::user()->role . '.laporan.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Laporan Statistik Periode</h1>
                <p class="text-gray-600 mt-1">Summary bulanan & tahunan dengan grafik lengkap</p>
            </div>
        </div>
        
        <div class="flex space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </button>
            <a href="{{ route(Auth::user()->role . '.laporan.export.pdf', ['type' => 'periode']) }}" 
               class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Toggle & Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route(Auth::user()->role . '.laporan.periode') }}" class="space-y-4">
            <!-- Toggle Bulanan/Tahunan -->
            <div class="flex items-center space-x-4 pb-4 border-b">
                <label class="text-sm font-semibold text-gray-700">Tipe Periode:</label>
                <div class="flex space-x-2">
                    <button type="button" onclick="document.getElementById('typeInput').value='monthly'; this.form.submit();" 
                        class="px-6 py-2 rounded-lg font-medium transition-all {{ $type === 'monthly' ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        📅 Bulanan
                    </button>
                    <button type="button" onclick="document.getElementById('typeInput').value='yearly'; this.form.submit();" 
                        class="px-6 py-2 rounded-lg font-medium transition-all {{ $type === 'yearly' ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        📊 Tahunan
                    </button>
                </div>
                <input type="hidden" name="type" id="typeInput" value="{{ $type }}">
            </div>

            <!-- Filter Form -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($type === 'monthly')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                    <select name="month" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                    <select name="year" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2.5 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-lg hover:from-orange-700 hover:to-red-700 transition-all shadow-lg font-medium">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Period Info -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 mb-1">Periode Laporan</p>
                <h2 class="text-2xl font-bold">
                    @if($type === 'monthly')
                        {{ $startDate->format('F Y') }}
                    @else
                        Tahun {{ $year }}
                    @endif
                </h2>
                <p class="text-sm opacity-90 mt-2">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm opacity-90 mb-1">Rentang Waktu</p>
                <p class="text-3xl font-bold">{{ $startDate->diffInDays($endDate) + 1 }}</p>
                <p class="text-sm opacity-90">Hari</p>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                @if($comparison['archives']['percentage'] >= 0)
                    <span class="text-green-600 text-sm font-semibold">↗ +{{ $comparison['archives']['percentage'] }}%</span>
                @else
                    <span class="text-red-600 text-sm font-semibold">↘ {{ $comparison['archives']['percentage'] }}%</span>
                @endif
            </div>
            <p class="text-sm text-gray-600 mb-1">Total Arsip</p>
            <p class="text-3xl font-bold text-gray-800">{{ $archiveStats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-2">vs periode sebelumnya: {{ $comparison['archives']['previous'] }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                @if($comparison['dispositions']['percentage'] >= 0)
                    <span class="text-green-600 text-sm font-semibold">↗ +{{ $comparison['dispositions']['percentage'] }}%</span>
                @else
                    <span class="text-red-600 text-sm font-semibold">↘ {{ $comparison['dispositions']['percentage'] }}%</span>
                @endif
            </div>
            <p class="text-sm text-gray-600 mb-1">Total Disposisi</p>
            <p class="text-3xl font-bold text-gray-800">{{ $dispositionStats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-2">vs periode sebelumnya: {{ $comparison['dispositions']['previous'] }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $dispositionStats['pending'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Disposisi belum diproses</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Selesai</p>
            <p class="text-3xl font-bold text-green-600">{{ $dispositionStats['completed'] }}</p>
            <p class="text-xs text-gray-500 mt-2">
                @if($dispositionStats['total'] > 0)
                    {{ round(($dispositionStats['completed'] / $dispositionStats['total']) * 100, 1) }}% completion rate
                @else
                    0% completion rate
                @endif
            </p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Main Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
                Tren Arsip {{ $type === 'monthly' ? 'per Hari' : 'per Bulan' }}
            </h3>
            <canvas id="archivesTrendChart" height="300"></canvas>
        </div>

        <!-- Category Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                Arsip per Kategori
            </h3>
            <canvas id="categoryChart" height="300"></canvas>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Breakdown per Kategori</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Persentase</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($archiveStats['by_category'] as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $item->category }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 text-blue-700 font-bold">
                                {{ $item->total }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-gradient-to-r from-orange-500 to-red-600 h-2 rounded-full" 
                                         style="width: {{ ($item->total / $archiveStats['total']) * 100 }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ round(($item->total / $archiveStats['total']) * 100, 1) }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Archives Trend Chart
const archivesTrendCtx = document.getElementById('archivesTrendChart').getContext('2d');
new Chart(archivesTrendCtx, {
    type: 'line',
    data: {
        labels: @json(array_keys($chartData->toArray())),
        datasets: [{
            label: 'Arsip',
            data: @json(array_values($chartData->toArray())),
            borderColor: 'rgb(249, 115, 22)',
            backgroundColor: 'rgba(249, 115, 22, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: @json($archiveStats['by_category']->pluck('category')->toArray()),
        datasets: [{
            data: @json($archiveStats['by_category']->pluck('total')->toArray()),
            backgroundColor: [
                'rgb(59, 130, 246)',
                'rgb(168, 85, 247)',
                'rgb(34, 197, 94)',
                'rgb(249, 115, 22)',
                'rgb(239, 68, 68)',
                'rgb(236, 72, 153)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endsection