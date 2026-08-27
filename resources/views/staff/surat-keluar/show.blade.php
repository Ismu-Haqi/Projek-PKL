@extends('staff.layouts.app')

@section('title', 'Detail Surat Keluar')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('staff.surat-keluar.index') }}" class="hover:text-teal-600">Surat Keluar</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">{{ $letter->nomor_agenda }}</span>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800">📤 {{ $letter->nomor_agenda }}</h1>
                <p class="text-gray-400 text-xs mt-1">Dicatat {{ $letter->created_at->format('d M Y H:i') }}</p>
            </div>
            @php $badge = $letter->status_badge; @endphp
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                {{ $badge['color'] === 'gray'   ? 'bg-gray-100 text-gray-700'   : '' }}
                {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $badge['color'] === 'green'  ? 'bg-green-100 text-green-800'   : '' }}
                {{ $badge['color'] === 'blue'   ? 'bg-blue-100 text-blue-800'     : '' }}">
                {{ $badge['text'] }}
            </span>
        </div>

        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Nomor Surat</dt><dd class="font-medium">{{ $letter->nomor_surat ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Tanggal Surat</dt><dd class="font-medium">{{ $letter->tanggal_surat->format('d M Y') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Tujuan</dt><dd class="font-medium">{{ $letter->tujuan }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Sifat</dt><dd class="font-medium">{{ ucfirst($letter->sifat) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Dicatat oleh</dt><dd class="font-medium">{{ $letter->pembuat->name ?? '-' }}</dd></div>
        </dl>

        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Perihal</p>
            <p class="text-sm text-gray-700">{{ $letter->perihal }}</p>
        </div>

        @if($letter->keterangan)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Keterangan</p>
            <p class="text-sm text-gray-700">{{ $letter->keterangan }}</p>
        </div>
        @endif

        @if($letter->file_path)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('staff.surat-keluar.download', $letter->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition">
                Unduh Lampiran ({{ $letter->file_size }})
            </a>
        </div>
        @endif

        <div class="mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('staff.surat-keluar.index') }}" class="text-sm text-teal-600 hover:underline">← Kembali ke daftar</a>
        </div>
    </div>
</div>
@endsection
