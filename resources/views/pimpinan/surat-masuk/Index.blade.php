@extends('pimpinan.layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div class="p-6">

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
        <p class="text-green-800 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Header — pimpinan tidak bisa input --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📨 Surat Masuk</h1>
            <p class="text-sm text-gray-500 mt-1">Monitoring seluruh surat masuk yang diterima</p>
        </div>
        {{-- Pimpinan hanya lihat, tidak ada tombol input --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-blue-700 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Mode Lihat Saja
        </div>
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
        <form method="GET" action="{{ route('pimpinan.surat-masuk.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari surat</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nomor, pengirim, perihal..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua Status</option>
                    <option value="belum_disposisi" {{ request('status') === 'belum_disposisi' ? 'selected' : '' }}>Belum Disposisi</option>
                    <option value="sudah_disposisi" {{ request('status') === 'sudah_disposisi' ? 'selected' : '' }}>Sudah Disposisi</option>
                    <option value="selesai"         {{ request('status') === 'selesai'         ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Sifat</label>
                <select name="sifat" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua Sifat</option>
                    <option value="biasa"         {{ request('sifat') === 'biasa'         ? 'selected' : '' }}>Biasa</option>
                    <option value="segera"        {{ request('sifat') === 'segera'        ? 'selected' : '' }}>Segera</option>
                    <option value="sangat_segera" {{ request('sifat') === 'sangat_segera' ? 'selected' : '' }}>Sangat Segera</option>
                    <option value="rahasia"       {{ request('sifat') === 'rahasia'       ? 'selected' : '' }}>Rahasia</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                Filter
            </button>
            @if(request()->hasAny(['search','status','sifat']))
            <a href="{{ route('pimpinan.surat-masuk.index') }}" class="text-gray-500 px-4 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50 transition">
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Diinput oleh</th>
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
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $letter->uploader->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{-- Pimpinan hanya bisa lihat dan download --}}
                            <div class="flex items-center gap-2">
                                <a href="{{ route('pimpinan.surat-masuk.show', $letter->id) }}"
                                   class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition" title="Lihat detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($letter->file_path)
                                <a href="{{ route('pimpinan.surat-masuk.download', $letter->id) }}"
                                   class="text-green-600 hover:text-green-800 p-1 rounded hover:bg-green-50 transition" title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="font-medium">Belum ada surat masuk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($letters->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $letters->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
