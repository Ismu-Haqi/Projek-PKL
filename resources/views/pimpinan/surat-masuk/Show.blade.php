@extends('pimpinan.layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    {{-- Header --}}
    <div class="flex items-center mb-6">
        <a href="{{ route('pimpinan.surat-masuk.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-800">Detail Surat Masuk</h1>
            <p class="text-gray-500 text-sm mt-0.5">{{ $letter->nomor_agenda }}</p>
        </div>

        {{-- Tombol aksi --}}
        <div class="flex gap-2">
            @if($letter->status === 'belum_disposisi')
            <a href="{{ route('pimpinan.surat-masuk.buat-disposisi', $letter->id) }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Buat Disposisi
            </a>
            <a href="{{ route('pimpinan.surat-masuk.edit', $letter->id) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            @endif

            @if($letter->file_path)
            <a href="{{ route('pimpinan.surat-masuk.download', $letter->id) }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download
            </a>
            @endif
        </div>
    </div>

    {{-- Status Banner --}}
    @php $status = $letter->status_badge; @endphp
    <div class="mb-6 p-4 rounded-xl border flex items-center gap-3
        {{ $status['color'] === 'yellow' ? 'bg-yellow-50 border-yellow-300' : '' }}
        {{ $status['color'] === 'blue'   ? 'bg-blue-50 border-blue-300'     : '' }}
        {{ $status['color'] === 'green'  ? 'bg-green-50 border-green-300'   : '' }}">
        <span class="text-2xl">
            {{ $status['color'] === 'yellow' ? '⏳' : ($status['color'] === 'blue' ? '📋' : '✅') }}
        </span>
        <div>
            <p class="font-semibold text-gray-800">{{ $status['text'] }}</p>
            @if($letter->disposisi_at)
            <p class="text-sm text-gray-500">Didisposisi pada: {{ $letter->disposisi_at->translatedFormat('d F Y, H:i') }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Data Surat (kiri) --}}
        <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h2 class="font-bold text-gray-800 text-lg border-b pb-2">Informasi Surat</h2>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 font-medium">Nomor Agenda</p>
                    <p class="font-mono font-semibold text-blue-700 mt-0.5">{{ $letter->nomor_agenda }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Nomor Surat</p>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $letter->nomor_surat }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Tanggal Surat</p>
                    <p class="text-gray-800 mt-0.5">{{ $letter->tanggal_surat->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Tanggal Diterima</p>
                    <p class="text-gray-800 mt-0.5">{{ $letter->tanggal_diterima->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Pengirim</p>
                    <p class="text-gray-800 font-semibold mt-0.5">{{ $letter->pengirim }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Sifat</p>
                    @php $sifat = $letter->sifat_badge; @endphp
                    <span class="mt-1 inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                        {{ $sifat['color'] === 'red'    ? 'bg-red-100 text-red-700'       : '' }}
                        {{ $sifat['color'] === 'orange' ? 'bg-orange-100 text-orange-700' : '' }}
                        {{ $sifat['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $sifat['color'] === 'gray'   ? 'bg-gray-100 text-gray-700'     : '' }}">
                        {{ $sifat['text'] }}
                    </span>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-500 font-medium">Perihal</p>
                    <p class="text-gray-800 font-semibold mt-0.5">{{ $letter->perihal }}</p>
                </div>
                @if($letter->kategori)
                <div>
                    <p class="text-gray-500 font-medium">Kategori</p>
                    <p class="text-gray-800 mt-0.5">{{ $letter->kategori }}</p>
                </div>
                @endif
                @if($letter->unit_tujuan)
                <div>
                    <p class="text-gray-500 font-medium">Unit Tujuan</p>
                    <p class="text-gray-800 mt-0.5">{{ $letter->unit_tujuan }}</p>
                </div>
                @endif
                @if($letter->keterangan)
                <div class="col-span-2">
                    <p class="text-gray-500 font-medium">Keterangan</p>
                    <p class="text-gray-700 mt-0.5">{{ $letter->keterangan }}</p>
                </div>
                @endif
                <div>
                    <p class="text-gray-500 font-medium">Diinput oleh</p>
                    <p class="text-gray-800 mt-0.5">{{ $letter->uploader->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Tanggal Input</p>
                    <p class="text-gray-800 mt-0.5">{{ $letter->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Sidebar kanan --}}
        <div class="space-y-4">

            {{-- Info file --}}
            @if($letter->file_path)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <h3 class="font-semibold text-gray-700 mb-3 text-sm">File Surat</h3>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="bg-red-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-700 truncate">{{ $letter->file_name }}</p>
                        <p class="text-xs text-gray-400">{{ $letter->file_size_formatted }} · {{ $letter->file_type }}</p>
                    </div>
                </div>
                <div class="flex gap-2 mt-3">
                    <a href="{{ route('pimpinan.surat-masuk.preview', $letter->id) }}" target="_blank"
                       class="flex-1 text-center text-xs py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium transition">
                        Preview
                    </a>
                    <a href="{{ route('pimpinan.surat-masuk.download', $letter->id) }}"
                       class="flex-1 text-center text-xs py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 font-medium transition">
                        Download
                    </a>
                </div>
            </div>
            @endif

            {{-- Info disposisi --}}
            @if($letter->disposition)
            <div class="bg-purple-50 rounded-xl border border-purple-200 p-4">
                <h3 class="font-semibold text-purple-700 mb-2 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Disposisi Terkait
                </h3>
                <p class="text-xs font-mono text-purple-600 font-semibold">{{ $letter->disposition->nomor_disposisi }}</p>
                <p class="text-xs text-gray-600 mt-1">Ke: {{ $letter->disposition->toUser->name ?? '-' }}</p>
                <a href="{{ route('pimpinan.disposisi.show', $letter->disposition->id) }}"
                   class="mt-2 inline-block text-xs text-purple-600 hover:underline font-medium">
                    Lihat Disposisi →
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection