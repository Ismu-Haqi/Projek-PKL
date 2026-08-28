@extends('pimpinan.layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📤 Surat Keluar</h1>
            <p class="text-sm text-gray-500 mt-1">Tinjau dan tandatangani (TTE) surat keluar yang diajukan</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-teal-500">
            <p class="text-xs text-gray-500">Total Surat Keluar</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-400">
            <p class="text-xs text-gray-500">Menunggu TTE</p>
            <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['menunggu_tte'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-400">
            <p class="text-xs text-gray-500">Bulan Ini</p>
            <h3 class="text-2xl font-bold text-blue-600">{{ $stats['bulan_ini'] }}</h3>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow p-4 mb-5">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nomor agenda / perihal / tujuan..."
                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua Status</option>
                <option value="menunggu_tte" {{ request('status') === 'menunggu_tte' ? 'selected' : '' }}>Menunggu TTE</option>
                <option value="ditandatangani" {{ request('status') === 'ditandatangani' ? 'selected' : '' }}>Sudah Ditandatangani</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 transition">Cari</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">No. Agenda</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tujuan</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Perihal</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Diajukan Oleh</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($letters as $letter)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono font-semibold text-teal-700 text-xs">{{ $letter->nomor_agenda }}</td>
                    <td class="px-4 py-3">{{ $letter->tujuan }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($letter->perihal, 40) }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $letter->pembuat->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php $badge = $letter->status_badge; @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $badge['color'] === 'gray'   ? 'bg-gray-100 text-gray-700'   : '' }}
                            {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $badge['color'] === 'green'  ? 'bg-green-100 text-green-800'   : '' }}
                            {{ $badge['color'] === 'red'    ? 'bg-red-100 text-red-800'       : '' }}
                            {{ $badge['color'] === 'blue'   ? 'bg-blue-100 text-blue-800'     : '' }}">
                            {{ $badge['text'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('pimpinan.surat-keluar.show', $letter->id) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition font-medium">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">Belum ada surat keluar.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($letters->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $letters->links() }}</div>
        @endif
    </div>
</div>
@endsection
