@extends('staff.layouts.app')

@section('title', 'Mutasi Aset')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">🔄 Mutasi Aset</h1>
            <p class="text-sm text-gray-500 mt-1">Pencatatan perpindahan aset permanen antar unit/bidang</p>
        </div>
        <a href="{{ route('staff.mutasi.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Ajukan Mutasi
        </a>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500">Total Mutasi</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-400">
            <p class="text-xs text-gray-500">Menunggu</p>
            <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['menunggu'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500">Disetujui</p>
            <h3 class="text-2xl font-bold text-green-600">{{ $stats['disetujui'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-xs text-gray-500">Ditolak</p>
            <h3 class="text-2xl font-bold text-red-600">{{ $stats['ditolak'] }}</h3>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-lg shadow p-4 mb-5">
        <form method="GET" action="{{ route('staff.mutasi.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nomor mutasi / nama aset..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">Filter</button>
            <a href="{{ route('staff.mutasi.index') }}" class="text-gray-500 px-4 py-2 rounded-lg text-sm border hover:bg-gray-50 transition">Reset</a>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">No. Mutasi</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Aset</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Perpindahan</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Pengaju</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($mutations as $mutation)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono font-semibold text-blue-700 text-xs">{{ $mutation->nomor_mutasi }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-800">{{ $mutation->asset->nama ?? '-' }}</div>
                        <div class="text-xs text-gray-400">{{ $mutation->asset->kode_asset ?? '' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="bg-orange-100 text-orange-700 px-2 py-0.5 rounded">{{ $mutation->unit_asal }}</span>
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded">{{ $mutation->unit_tujuan }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $mutation->tanggal_mutasi->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $mutation->pengaju->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php $badge = $mutation->status_badge; @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $badge['color'] === 'green'  ? 'bg-green-100 text-green-800'   : '' }}
                            {{ $badge['color'] === 'red'    ? 'bg-red-100 text-red-800'       : '' }}">
                            {{ $badge['text'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('staff.mutasi.show', $mutation->id) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        <p class="font-medium">Belum ada data mutasi</p>
                        <p class="text-sm mt-1">Klik "Ajukan Mutasi" untuk memulai</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($mutations->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $mutations->links() }}</div>
        @endif
    </div>
</div>
@endsection
