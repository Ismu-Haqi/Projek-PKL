@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Laporan Aktivitas User')

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
                <h1 class="text-3xl font-bold text-gray-800">👥 Laporan Aktivitas User</h1>
                <p class="text-gray-600 mt-1">Monitoring aktivitas & kinerja pengguna sistem</p>
            </div>
        </div>
    </div>

    <!-- ✅ FILTER PERIODE (Modern UI) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route(Auth::user()->role . '.laporan.user') }}">
            
            <!-- Period Toggle Buttons -->
            <div class="flex flex-wrap gap-2 mb-4">
                <button type="submit" name="period" value="1month" 
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period', '1month') === '1month' ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 1 Bulan
                </button>
                <button type="submit" name="period" value="3months" 
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === '3months' ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 3 Bulan
                </button>
                <button type="submit" name="period" value="6months" 
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === '6months' ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 6 Bulan
                </button>
                <button type="submit" name="period" value="1year" 
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === '1year' ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 1 Tahun
                </button>
                <button type="button" onclick="toggleCustomDate()" 
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === 'custom' ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    🗓️ Custom
                </button>
            </div>

            <!-- Custom Date Range (Hidden by default) -->
            <div id="customDateRange" class="mb-4 {{ request('period') === 'custom' ? '' : 'hidden' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <input type="hidden" name="period" value="custom">
                <button type="submit" class="mt-4 px-6 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all shadow-lg font-medium">
                    Terapkan Custom Range
                </button>
            </div>

            <div class="text-sm text-gray-600">
                <strong>Periode:</strong> {{ $dateRange['label'] ?? 'Semua Periode' }}
                <span class="ml-3">
                    ({{ $dateRange['start']->format('d M Y') }} - {{ $dateRange['end']->format('d M Y') }})
                </span>
            </div>
        </form>
    </div>

    @include('partials.laporan-buttons', ['type' => 'user'])

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Total Pengguna</p>
            <p class="text-3xl font-bold text-gray-800">{{ $users->total() }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Admin</p>
            <p class="text-3xl font-bold text-purple-600">{{ $users->where('role', 'admin')->count() }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Staff</p>
            <p class="text-3xl font-bold text-green-600">{{ $users->where('role', 'staff')->count() }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Total Arsip (Periode)</p>
            <p class="text-3xl font-bold text-orange-600">{{ $users->sum('archives_count') }}</p>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Aktivitas Pengguna (Periode: {{ $dateRange['label'] }})</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-50 to-teal-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Email</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Unit</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Arsip</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Disp. Terkirim</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Disp. Diterima</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <span class="text-blue-600 font-semibold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                    Admin
                                </span>
                            @elseif($user->role === 'pimpinan')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    Pimpinan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    Staff
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->unit ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-700 font-bold text-sm">
                                {{ $user->archives_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 text-blue-700 font-bold text-sm">
                                {{ $user->sent_dispositions_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-purple-100 text-purple-700 font-bold text-sm">
                                {{ $user->received_dispositions_count }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Tidak ada data pengguna
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<script>
// Toggle Custom Date Range
function toggleCustomDate() {
    const customDiv = document.getElementById('customDateRange');
    customDiv.classList.toggle('hidden');
}
</script>
@endsection