@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Laporan Surat Masuk')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route(Auth::user()->role . '.laporan.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">📨 Laporan Surat Masuk</h1>
                <p class="text-gray-600 mt-1">
                    @if(Auth::user()->role === 'staff')
                        Rekap surat masuk yang Anda input beserta status disposisinya
                    @else
                        Monitoring seluruh surat masuk — Diskominfo Kab. Barito Kuala
                    @endif
                </p>
            </div>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route(Auth::user()->role . '.laporan.print-pdf', ['type' => 'surat-masuk']) }}" target="_blank"
               class="flex items-center gap-2 bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print PDF
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route(Auth::user()->role . '.laporan.surat-masuk') }}">

            {{-- Period buttons --}}
            <div class="flex flex-wrap gap-2 mb-4 pb-4 border-b">
                <button type="submit" name="period" value="1month"
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period', '1month') === '1month' ? 'bg-teal-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 1 Bulan
                </button>
                <button type="submit" name="period" value="3months"
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === '3months' ? 'bg-teal-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 3 Bulan
                </button>
                <button type="submit" name="period" value="6months"
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === '6months' ? 'bg-teal-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 6 Bulan
                </button>
                <button type="submit" name="period" value="1year"
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === '1year' ? 'bg-teal-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📅 1 Tahun
                </button>
                <button type="submit" name="period" value="all"
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === 'all' ? 'bg-teal-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    📋 Semua
                </button>
                <button type="button" onclick="toggleCustomDate()"
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ request('period') === 'custom' ? 'bg-teal-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    🗓️ Custom
                </button>
            </div>

            {{-- Custom date --}}
            <div id="customDateRange" class="{{ request('period') === 'custom' ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
                </div>
                <input type="hidden" name="period" value="custom">
            </div>

            {{-- Filter tambahan --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Status</option>
                        <option value="belum_disposisi" {{ request('status') === 'belum_disposisi' ? 'selected' : '' }}>Belum Disposisi</option>
                        <option value="sudah_disposisi" {{ request('status') === 'sudah_disposisi' ? 'selected' : '' }}>Sudah Disposisi</option>
                        <option value="selesai"         {{ request('status') === 'selesai'         ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sifat Surat</label>
                    <select name="sifat" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Sifat</option>
                        <option value="biasa"         {{ request('sifat') === 'biasa'         ? 'selected' : '' }}>Biasa</option>
                        <option value="segera"        {{ request('sifat') === 'segera'        ? 'selected' : '' }}>Segera</option>
                        <option value="sangat_segera" {{ request('sifat') === 'sangat_segera' ? 'selected' : '' }}>Sangat Segera</option>
                        <option value="rahasia"       {{ request('sifat') === 'rahasia'       ? 'selected' : '' }}>Rahasia</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        🔍 Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-l-4 border-teal-500 p-4">
            <p class="text-xs text-gray-500 font-medium">Total Surat</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] ?? 0 }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-l-4 border-yellow-400 p-4">
            <p class="text-xs text-gray-500 font-medium">Belum Disposisi</p>
            <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['belum_disposisi'] ?? 0 }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-l-4 border-blue-400 p-4">
            <p class="text-xs text-gray-500 font-medium">Sudah Disposisi</p>
            <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['sudah_disposisi'] ?? 0 }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-l-4 border-green-500 p-4">
            <p class="text-xs text-gray-500 font-medium">Selesai</p>
            <h3 class="text-2xl font-bold text-green-600 mt-1">{{ $stats['selesai'] ?? 0 }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-l-4 border-purple-500 p-4">
            <p class="text-xs text-gray-500 font-medium">Bulan Ini</p>
            <h3 class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['bulan_ini'] ?? 0 }}</h3>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Daftar Surat Masuk</h3>
            <span class="text-sm text-gray-500">{{ isset($letters) ? $letters->total() : 0 }} surat</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No. Agenda</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tgl Diterima</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pengirim</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Perihal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Sifat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        @if(Auth::user()->role !== 'staff')
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Diinput Oleh</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($letters as $index => $letter)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ ($letters->currentPage() - 1) * $letters->perPage() + $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route(Auth::user()->role . '.surat-masuk.show', $letter->id) }}"
                               class="font-mono text-xs font-semibold text-teal-700 bg-teal-50 px-2 py-1 rounded hover:bg-teal-100 transition">
                                {{ $letter->nomor_agenda }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $letter->tanggal_diterima->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800 font-medium max-w-xs truncate">
                            {{ $letter->pengirim }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate">
                            {{ $letter->perihal }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $sifatColors = [
                                    'biasa'         => 'bg-gray-100 text-gray-700',
                                    'segera'        => 'bg-yellow-100 text-yellow-700',
                                    'sangat_segera' => 'bg-orange-100 text-orange-700',
                                    'rahasia'       => 'bg-red-100 text-red-700',
                                ];
                                $sifatLabels = [
                                    'biasa'         => 'Biasa',
                                    'segera'        => 'Segera',
                                    'sangat_segera' => 'Sangat Segera',
                                    'rahasia'       => 'Rahasia',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $sifatColors[$letter->sifat] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $sifatLabels[$letter->sifat] ?? $letter->sifat }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'belum_disposisi' => 'bg-yellow-100 text-yellow-700',
                                    'sudah_disposisi' => 'bg-blue-100 text-blue-700',
                                    'selesai'         => 'bg-green-100 text-green-700',
                                ];
                                $statusLabels = [
                                    'belum_disposisi' => 'Belum Disposisi',
                                    'sudah_disposisi' => 'Sudah Disposisi',
                                    'selesai'         => 'Selesai',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$letter->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$letter->status] ?? $letter->status }}
                            </span>
                        </td>
                        @if(Auth::user()->role !== 'staff')
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $letter->uploader->name ?? '-' }}
                            @if($letter->uploader->unit ?? null)
                            <span class="text-gray-400 text-xs block">{{ $letter->uploader->unit }}</span>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="font-medium">Belum ada data surat masuk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($letters) && $letters->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $letters->links() }}
        </div>
        @endif
    </div>

</div>

<script>
function toggleCustomDate() {
    const el = document.getElementById('customDateRange');
    el.classList.toggle('hidden');
}
</script>
@endsection
