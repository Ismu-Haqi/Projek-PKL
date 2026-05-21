@extends('admin.layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div class="p-6">

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg animate-fade-in">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📨 Surat Masuk</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola surat masuk dan proses disposisi</p>
        </div>
        <a href="{{ route('admin.surat-masuk.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg flex items-center transition duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Input Surat Masuk
        </a>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500 font-medium">Total Surat</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-400">
            <p class="text-sm text-gray-500 font-medium">Belum Disposisi</p>
            <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['belum_disposisi'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-400">
            <p class="text-sm text-gray-500 font-medium">Sudah Disposisi</p>
            <h3 class="text-2xl font-bold text-blue-600">{{ $stats['sudah_disposisi'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-500 font-medium">Selesai</p>
            <h3 class="text-2xl font-bold text-green-600">{{ $stats['selesai'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <p class="text-sm text-gray-500 font-medium">Bulan Ini</p>
            <h3 class="text-2xl font-bold text-purple-600">{{ $stats['bulan_ini'] }}</h3>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.surat-masuk.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari surat</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nomor, pengirim, perihal..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="belum_disposisi" {{ request('status') === 'belum_disposisi' ? 'selected' : '' }}>Belum Disposisi</option>
                    <option value="sudah_disposisi" {{ request('status') === 'sudah_disposisi' ? 'selected' : '' }}>Sudah Disposisi</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Sifat</label>
                <select name="sifat" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Sifat</option>
                    <option value="biasa"         {{ request('sifat') === 'biasa'         ? 'selected' : '' }}>Biasa</option>
                    <option value="segera"        {{ request('sifat') === 'segera'        ? 'selected' : '' }}>Segera</option>
                    <option value="sangat_segera" {{ request('sifat') === 'sangat_segera' ? 'selected' : '' }}>Sangat Segera</option>
                    <option value="rahasia"       {{ request('sifat') === 'rahasia'       ? 'selected' : '' }}>Rahasia</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Filter
            </button>
            @if(request()->hasAny(['search','status','sifat']))
            <a href="{{ route('admin.surat-masuk.index') }}" class="text-gray-500 px-4 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50 transition">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No. Agenda</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tgl Diterima</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pengirim</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Perihal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Sifat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($letters as $letter)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <span class="font-mono text-xs font-semibold text-blue-700 bg-blue-50 px-2 py-1 rounded">
                                {{ $letter->nomor_agenda }}
                            </span>
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
                            @php $sifat = $letter->sifat_badge; @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $sifat['color'] === 'red'    ? 'bg-red-100 text-red-700'       : '' }}
                                {{ $sifat['color'] === 'orange' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $sifat['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $sifat['color'] === 'gray'   ? 'bg-gray-100 text-gray-700'     : '' }}">
                                {{ $sifat['text'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @php $status = $letter->status_badge; @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $status['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $status['color'] === 'blue'   ? 'bg-blue-100 text-blue-700'     : '' }}
                                {{ $status['color'] === 'green'  ? 'bg-green-100 text-green-700'   : '' }}">
                                {{ $status['text'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.surat-masuk.show', $letter->id) }}"
                                   class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition" title="Lihat detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                @if($letter->status === 'belum_disposisi')
                                <a href="{{ route('admin.surat-masuk.buat-disposisi', $letter->id) }}"
                                   class="text-purple-600 hover:text-purple-800 p-1 rounded hover:bg-purple-50 transition" title="Buat disposisi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.surat-masuk.edit', $letter->id) }}"
                                   class="text-yellow-600 hover:text-yellow-800 p-1 rounded hover:bg-yellow-50 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.surat-masuk.destroy', $letter->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus surat ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="font-medium">Belum ada surat masuk</p>
                            <a href="{{ route('admin.surat-masuk.create') }}" class="text-blue-600 hover:underline text-sm mt-1 inline-block">
                                + Input surat pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($letters->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $letters->links() }}
        </div>
        @endif
    </div>
</div>
@endsection