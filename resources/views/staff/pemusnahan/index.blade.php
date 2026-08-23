@extends('staff.layouts.app')

@section('title', 'Pemusnahan Aset')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">🗑️ Pemusnahan Aset</h1>
            <p class="text-sm text-gray-500 mt-1">Usulan pemusnahan aset yang sudah rusak/tidak layak pakai</p>
        </div>
        <a href="{{ route('staff.pemusnahan.create') }}"
           class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Ajukan Pemusnahan
        </a>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-xs text-gray-500">Total Usulan</p>
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
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-400">
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
        <form method="GET" action="{{ route('staff.pemusnahan.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nomor usulan / nama aset..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">Filter</button>
            <a href="{{ route('staff.pemusnahan.index') }}" class="text-gray-500 px-4 py-2 rounded-lg text-sm border hover:bg-gray-50 transition">Reset</a>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">No. Usulan</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Aset</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal Usulan</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($destructions as $destruction)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono font-semibold text-red-700 text-xs">{{ $destruction->nomor_pemusnahan }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-800">{{ $destruction->asset->nama ?? '-' }}</div>
                        <div class="text-xs text-gray-400">{{ $destruction->asset->kode_asset ?? '' }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $destruction->tanggal_usulan->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        @php $badge = $destruction->status_badge; @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $badge['color'] === 'green'  ? 'bg-green-100 text-green-800'   : '' }}
                            {{ $badge['color'] === 'red'    ? 'bg-red-100 text-red-800'       : '' }}">
                            {{ $badge['text'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('staff.pemusnahan.show', $destruction->id) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <p class="font-medium">Belum ada usulan pemusnahan</p>
                        <p class="text-sm mt-1">Klik "Ajukan Pemusnahan" untuk memulai</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($destructions->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $destructions->links() }}</div>
        @endif
    </div>
</div>
@endsection
