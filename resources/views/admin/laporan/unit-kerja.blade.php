@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Laporan Produktivitas Unit Kerja')

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
                <h1 class="text-3xl font-bold text-gray-800">Laporan Produktivitas Unit Kerja</h1>
                <p class="text-gray-600 mt-1">Perbandingan kinerja & aktivitas antar unit kerja</p>
            </div>
        </div>
        
        <div class="flex space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </button>
            <a href="{{ route(Auth::user()->role . '.laporan.export.pdf', ['type' => 'unit']) }}" 
               class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                Export PDF
            </a>
            <a href="{{ route(Auth::user()->role . '.laporan.export.excel', ['type' => 'unit']) }}" 
               class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route(Auth::user()->role . '.laporan.unit-kerja') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Urutkan Berdasarkan</label>
                <select name="sort_by" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="archives" {{ request('sort_by') == 'archives' ? 'selected' : '' }}>Total Arsip</option>
                    <option value="dispositions" {{ request('sort_by') == 'dispositions' ? 'selected' : '' }}>Total Disposisi</option>
                    <option value="completion_rate" {{ request('sort_by') == 'completion_rate' ? 'selected' : '' }}>Completion Rate</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg font-medium">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Period Info -->
    <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 mb-1">Periode Laporan</p>
                <h2 class="text-2xl font-bold">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</h2>
            </div>
            <div class="text-right">
                <p class="text-sm opacity-90 mb-1">Total Unit Kerja</p>
                <p class="text-3xl font-bold">{{ $units->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Top 3 Units -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($units->take(3) as $index => $unit)
        <div class="bg-white rounded-xl shadow-lg border-2 {{ $index == 0 ? 'border-yellow-400' : ($index == 1 ? 'border-gray-400' : 'border-orange-400') }} p-6 relative overflow-hidden">
            <!-- Trophy Badge -->
            <div class="absolute top-4 right-4">
                @if($index == 0)
                    <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                @elseif($index == 1)
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                @else
                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <p class="text-2xl font-bold text-gray-400">#{{ $index + 1 }}</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $unit->unit_kerja }}</h3>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Arsip</span>
                    <span class="text-lg font-bold text-blue-600">{{ $unit->total_archives }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Disposisi</span>
                    <span class="text-lg font-bold text-purple-600">{{ $unit->total_dispositions }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Completion Rate</span>
                    <span class="text-lg font-bold text-green-600">{{ $unit->completion_rate }}%</span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all" 
                         style="width: {{ $unit->completion_rate }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Comparison Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Archives by Unit Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Total Arsip per Unit
            </h3>
            <canvas id="archivesByUnitChart" height="300"></canvas>
        </div>

        <!-- Dispositions by Unit Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Total Disposisi per Unit
            </h3>
            <canvas id="dispositionsByUnitChart" height="300"></canvas>
        </div>
    </div>

    <!-- Completion Rate Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            Perbandingan Completion Rate
        </h3>
        <canvas id="completionRateChart" height="100"></canvas>
    </div>

    <!-- Detailed Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Produktivitas per Unit</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-50 to-pink-50 border-b-2 border-purple-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ranking</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Unit Kerja</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Total Arsip</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Total Disposisi</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Selesai</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Completion Rate</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($units as $index => $unit)
                    <tr class="hover:bg-purple-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($index < 3)
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white text-sm
                                        {{ $index == 0 ? 'bg-yellow-500' : ($index == 1 ? 'bg-gray-400' : 'bg-orange-500') }}">
                                        {{ $index + 1 }}
                                    </span>
                                @else
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-gray-600 text-sm bg-gray-100">
                                        {{ $index + 1 }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-gray-800">{{ $unit->unit_kerja }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-bold text-sm">
                                {{ $unit->total_archives }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-purple-100 text-purple-700 font-bold text-sm">
                                {{ $unit->total_dispositions }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-green-100 text-green-700 font-bold text-sm">
                                {{ $unit->completed_dispositions }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center">
                                <div class="flex-1 max-w-xs bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all" 
                                         style="width: {{ $unit->completion_rate }}%"></div>
                                </div>
                                <span class="text-sm font-bold text-gray-700 min-w-[50px] text-right">
                                    {{ $unit->completion_rate }}%
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($unit->completion_rate >= 80)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Excellent
                                </span>
                            @elseif($unit->completion_rate >= 60)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Good
                                </span>
                            @elseif($unit->completion_rate >= 40)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Fair
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Need Improvement
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90 mb-1">Rata-rata Arsip per Unit</p>
            <p class="text-3xl font-bold">{{ number_format($units->avg('total_archives'), 1) }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90 mb-1">Rata-rata Disposisi per Unit</p>
            <p class="text-3xl font-bold">{{ number_format($units->avg('total_dispositions'), 1) }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90 mb-1">Rata-rata Completion Rate</p>
            <p class="text-3xl font-bold">{{ number_format($units->avg('completion_rate'), 1) }}%</p>
        </div>
        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90 mb-1">Unit Terbaik</p>
            <p class="text-xl font-bold truncate">{{ $units->first()->unit_kerja ?? '-' }}</p>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Archives by Unit Chart
const archivesCtx = document.getElementById('archivesByUnitChart').getContext('2d');
new Chart(archivesCtx, {
    type: 'bar',
    data: {
        labels: @json($units->pluck('unit_kerja')->toArray()),
        datasets: [{
            label: 'Total Arsip',
            data: @json($units->pluck('total_archives')->toArray()),
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgb(59, 130, 246)',
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

// Dispositions by Unit Chart
const dispositionsCtx = document.getElementById('dispositionsByUnitChart').getContext('2d');
new Chart(dispositionsCtx, {
    type: 'bar',
    data: {
        labels: @json($units->pluck('unit_kerja')->toArray()),
        datasets: [{
            label: 'Total Disposisi',
            data: @json($units->pluck('total_dispositions')->toArray()),
            backgroundColor: 'rgba(168, 85, 247, 0.8)',
            borderColor: 'rgb(168, 85, 247)',
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

// Completion Rate Chart
const completionCtx = document.getElementById('completionRateChart').getContext('2d');
new Chart(completionCtx, {
    type: 'line',
    data: {
        labels: @json($units->pluck('unit_kerja')->toArray()),
        datasets: [{
            label: 'Completion Rate (%)',
            data: @json($units->pluck('completion_rate')->toArray()),
            borderColor: 'rgb(236, 72, 153)',
            backgroundColor: 'rgba(236, 72, 153, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: 'rgb(236, 72, 153)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
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
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});
</script>
@endsection