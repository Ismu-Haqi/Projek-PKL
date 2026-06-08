@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Laporan Aset')

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
                <h1 class="text-3xl font-bold text-gray-800">📦 Laporan Aset</h1>
                <p class="text-gray-600 mt-1">Inventaris aset berdasarkan periode, kategori & kondisi</p>
            </div>
        </div>
    </div>

    <!-- ✅ FILTER PERIODE (1 Bulan, 3 Bulan, 6 Bulan, 1 Tahun, Custom) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route(Auth::user()->role . '.laporan.aset') }}">
            
            <!-- Period Toggle Buttons -->
            <div class="flex flex-wrap gap-2 mb-4 pb-4 border-b">
                <button type="submit" name="period" value="1month" 
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period', '1month') === '1month' ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 1 Bulan
                </button>
                <button type="submit" name="period" value="3months" 
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === '3months' ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 3 Bulan
                </button>
                <button type="submit" name="period" value="6months" 
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === '6months' ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 6 Bulan
                </button>
                <button type="submit" name="period" value="1year" 
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === '1year' ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 1 Tahun
                </button>
                <button type="button" onclick="toggleCustomDate()" 
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === 'custom' ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    🗓️ Custom
                </button>
            </div>

            <!-- Custom Date Range (Hidden by default) -->
            <div id="customDateRange" class="mb-4 pb-4 border-b {{ request('period') === 'custom' ? '' : 'hidden' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>
                <input type="hidden" name="period" value="custom">
                <button type="submit" class="mt-4 px-6 py-2.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all shadow-lg font-medium">
                    Terapkan Custom Range
                </button>
            </div>

            <!-- Additional Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Unit</label>
                    <select name="unit" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Semua Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>
                                {{ $unit }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Semua Status</option>
                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="digunakan" {{ request('status') == 'digunakan' ? 'selected' : '' }}>Digunakan</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="rusak" {{ request('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kondisi</label>
                    <select name="kondisi" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="cukup" {{ request('kondisi') == 'cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="kurang" {{ request('kondisi') == 'kurang' ? 'selected' : '' }}>Kurang</option>
                        <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <strong>Periode:</strong> {{ $dateRange['label'] ?? 'Semua Periode' }}
                    <span class="ml-3">
                        ({{ $dateRange['start']->format('d M Y') }} - {{ $dateRange['end']->format('d M Y') }})
                    </span>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-lg hover:from-orange-700 hover:to-red-700 transition-all shadow-lg font-medium">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    @include('partials.laporan-buttons', ['type' => 'aset'])

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
            <div class="text-sm text-blue-600 font-medium mb-1">Total Aset</div>
            <div class="text-3xl font-bold text-blue-700">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500 mt-2">Periode ini</div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200 p-6">
            <div class="text-sm text-green-600 font-medium mb-1">Kondisi Baik</div>
            <div class="text-3xl font-bold text-green-700">
                {{ $stats['by_kondisi']->where('kondisi', 'baik')->first()->total ?? 0 }}
            </div>
            <div class="text-xs text-gray-500 mt-2">Siap digunakan</div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl border border-yellow-200 p-6">
            <div class="text-sm text-yellow-600 font-medium mb-1">Dipinjam</div>
            <div class="text-3xl font-bold text-yellow-700">
                {{ $stats['by_status']->where('status', 'dipinjam')->first()->total ?? 0 }}
            </div>
            <div class="text-xs text-gray-500 mt-2">Sedang dipinjam</div>
        </div>

        <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-xl border border-red-200 p-6">
            <div class="text-sm text-red-600 font-medium mb-1">Rusak</div>
            <div class="text-3xl font-bold text-red-700">
                {{ $stats['by_kondisi']->where('kondisi', 'rusak')->first()->total ?? 0 }}
            </div>
            <div class="text-xs text-gray-500 mt-2">Perlu perbaikan</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assets by Category -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                </svg>
                Aset per Kategori
            </h3>
            <div class="relative" style="height: 300px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Assets by Condition -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Kondisi Aset
            </h3>
            <div class="relative" style="height: 300px;">
                <canvas id="kondisiChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Assets Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-orange-50 to-red-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Kode Aset</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Nama Aset</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Unit</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Kondisi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal Input</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($assets as $index => $asset)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $assets->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm font-semibold text-blue-600">{{ $asset->kode_asset }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $asset->nama }}</p>
                            @if($asset->merk)
                            <p class="text-xs text-gray-500">{{ $asset->merk }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $asset->kategori }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $asset->unit ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $kondisiColors = [
                                    'baik' => 'bg-green-100 text-green-700',
                                    'cukup' => 'bg-blue-100 text-blue-700',
                                    'kurang' => 'bg-yellow-100 text-yellow-700',
                                    'rusak' => 'bg-red-100 text-red-700'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $kondisiColors[$asset->kondisi] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($asset->kondisi) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'tersedia' => 'bg-green-100 text-green-700',
                                    'digunakan' => 'bg-blue-100 text-blue-700',
                                    'dipinjam' => 'bg-orange-100 text-orange-700',
                                    'maintenance' => 'bg-yellow-100 text-yellow-700',
                                    'rusak' => 'bg-red-100 text-red-700'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$asset->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $asset->statusBadge['text'] ?? ucfirst($asset->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $asset->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">Tidak ada data aset</p>
                                <p class="text-gray-400 text-sm mt-1">Coba ubah filter periode atau kategori</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($assets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $assets->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Toggle Custom Date Range
function toggleCustomDate() {
    const customDiv = document.getElementById('customDateRange');
    customDiv.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    // Category Chart
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        new Chart(categoryCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($stats['by_kategori']->pluck('kategori')),
                datasets: [{
                    data: @json($stats['by_kategori']->pluck('total')),
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
    }

    // Kondisi Chart
    const kondisiCtx = document.getElementById('kondisiChart');
    if (kondisiCtx) {
        new Chart(kondisiCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($stats['by_kondisi']->pluck('kondisi')),
                datasets: [{
                    label: 'Total',
                    data: @json($stats['by_kondisi']->pluck('total')),
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }
});
</script>
@endsection