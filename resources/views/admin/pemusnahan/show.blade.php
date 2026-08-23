@extends('admin.layouts.app')

@section('title', 'Detail Usulan Pemusnahan')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.pemusnahan.index') }}" class="hover:text-red-600">Pemusnahan Aset</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">{{ $destruction->nomor_pemusnahan }}</span>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Utama --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Header --}}
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">🗑️ {{ $destruction->nomor_pemusnahan }}</h1>
                        <p class="text-gray-400 text-xs mt-1">Diajukan {{ $destruction->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @php $badge = $destruction->status_badge; @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $badge['color'] === 'green'  ? 'bg-green-100 text-green-800'   : '' }}
                        {{ $badge['color'] === 'red'    ? 'bg-red-100 text-red-800'       : '' }}">
                        {{ $badge['text'] }}
                    </span>
                </div>

                {{-- Info Aset --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Aset yang Diusulkan</p>
                    <p class="font-semibold text-gray-800">{{ $destruction->asset->nama ?? '-' }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $destruction->asset->kode_asset ?? '' }}</p>
                    @if($destruction->asset)
                    <div class="mt-1.5 flex gap-3 text-xs text-gray-500">
                        <span>Kategori: {{ $destruction->asset->kategori ?? '-' }}</span>
                        <span>Unit: {{ $destruction->asset->unit ?? '-' }}</span>
                        <span>Status Saat Ini: {{ ucfirst($destruction->asset->status ?? '-') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Detail --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-700 mb-4 text-sm">📋 Detail Usulan</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Tanggal Usulan</dt>
                        <dd class="font-medium">{{ $destruction->tanggal_usulan->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Kondisi Aset</dt>
                        <dd class="font-medium">{{ $destruction->kondisi_aset ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Diajukan oleh</dt>
                        <dd class="font-medium">{{ $destruction->pengaju->name ?? '-' }}</dd>
                    </div>
                    @if($destruction->penyetuju)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Diproses oleh</dt>
                        <dd class="font-medium">{{ $destruction->penyetuju->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Tanggal Persetujuan</dt>
                        <dd class="font-medium">{{ $destruction->tanggal_persetujuan?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    @endif
                </dl>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Alasan Pemusnahan</p>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $destruction->alasan_pemusnahan }}</p>
                </div>

                @if($destruction->catatan_penolakan)
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-xs font-semibold text-red-600 uppercase mb-1">Catatan Penolakan</p>
                    <p class="text-sm text-red-700">{{ $destruction->catatan_penolakan }}</p>
                </div>
                @endif

                @if($destruction->berita_acara)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.pemusnahan.download-ba', $destruction->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Unduh Berita Acara (PDF)
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-4">

            {{-- Approve / Reject --}}
            @if($destruction->status === 'menunggu')
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold text-gray-700 mb-4 text-sm">⚙️ Tindakan</h3>
                <form method="POST" action="{{ route('admin.pemusnahan.approve', $destruction->id) }}" class="mb-3">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Setujui pemusnahan aset ini? Aset akan ditandai sebagai dimusnahkan dan Berita Acara akan dibuat otomatis atas nama Anda.')"
                            class="w-full py-2.5 bg-green-600 text-white rounded-lg font-semibold text-sm hover:bg-green-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Setujui Pemusnahan
                    </button>
                </form>
                <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                        class="w-full py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-lg font-semibold text-sm hover:bg-red-100 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak Usulan
                </button>
            </div>
            @endif

            {{-- Navigasi --}}
            <div class="bg-white rounded-xl shadow p-5">
                <a href="{{ route('admin.pemusnahan.index') }}"
                   class="w-full py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition flex items-center justify-center gap-2">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Penolakan --}}
<div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Tolak Usulan Pemusnahan</h3>
        <p class="text-sm text-gray-500 mb-4">Berikan alasan penolakan yang jelas untuk pengaju.</p>
        <form method="POST" action="{{ route('admin.pemusnahan.reject', $destruction->id) }}">
            @csrf
            <textarea name="catatan_penolakan" rows="4" required
                      placeholder="Tulis alasan penolakan..."
                      class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 mb-4"></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700">
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
