@extends('staff.layouts.app')

@section('title', 'Detail Mutasi')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('staff.mutasi.index') }}" class="hover:text-blue-600">Mutasi Aset</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">{{ $mutation->nomor_mutasi }}</span>
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
                        <h1 class="text-xl font-bold text-gray-800">🔄 {{ $mutation->nomor_mutasi }}</h1>
                        <p class="text-gray-400 text-xs mt-1">Diajukan {{ $mutation->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @php $badge = $mutation->status_badge; @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $badge['color'] === 'green'  ? 'bg-green-100 text-green-800'   : '' }}
                        {{ $badge['color'] === 'red'    ? 'bg-red-100 text-red-800'       : '' }}">
                        {{ $badge['text'] }}
                    </span>
                </div>

                {{-- Info Aset --}}
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Aset</p>
                    <p class="font-semibold text-gray-800">{{ $mutation->asset->nama ?? '-' }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $mutation->asset->kode_asset ?? '' }}</p>
                    @if($mutation->asset)
                    <div class="mt-1.5 flex gap-3 text-xs text-gray-500">
                        <span>Kategori: {{ $mutation->asset->kategori ?? '-' }}</span>
                        <span>Kondisi: {{ ucfirst($mutation->asset->kondisi ?? '-') }}</span>
                    </div>
                    @endif
                </div>

                {{-- Perpindahan --}}
                <div class="flex items-stretch gap-3">
                    <div class="flex-1 bg-orange-50 border border-orange-200 rounded-lg p-4 text-center">
                        <p class="text-xs text-orange-600 font-semibold uppercase mb-1">Unit Asal</p>
                        <p class="font-bold text-gray-800">{{ $mutation->unit_asal }}</p>
                        @if($mutation->lokasi_asal)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $mutation->lokasi_asal }}</p>
                        @endif
                    </div>
                    <div class="flex items-center">
                        <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </div>
                    <div class="flex-1 bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <p class="text-xs text-green-600 font-semibold uppercase mb-1">Unit Tujuan</p>
                        <p class="font-bold text-gray-800">{{ $mutation->unit_tujuan }}</p>
                        @if($mutation->lokasi_tujuan)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $mutation->lokasi_tujuan }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Detail --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-700 mb-4 text-sm">📋 Detail Pengajuan</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Tanggal Mutasi</dt>
                        <dd class="font-medium">{{ $mutation->tanggal_mutasi->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Diajukan oleh</dt>
                        <dd class="font-medium">{{ $mutation->pengaju->name ?? '-' }}</dd>
                    </div>
                    @if($mutation->penyetuju)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Diproses oleh</dt>
                        <dd class="font-medium">{{ $mutation->penyetuju->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Tanggal Persetujuan</dt>
                        <dd class="font-medium">{{ $mutation->tanggal_persetujuan?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    @endif
                </dl>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Alasan Mutasi</p>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $mutation->alasan }}</p>
                </div>

                @if($mutation->catatan_penolakan)
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-xs font-semibold text-red-600 uppercase mb-1">Catatan Penolakan</p>
                    <p class="text-sm text-red-700">{{ $mutation->catatan_penolakan }}</p>
                </div>
                @endif

                @if($mutation->berita_acara)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('staff.mutasi.download-ba', $mutation->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Unduh Berita Acara
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-4">

            {{-- Approve / Reject --}}
            @if($mutation->status === 'menunggu' && Auth::user()->role === 'admin')
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold text-gray-700 mb-4 text-sm">⚙️ Tindakan</h3>
                <form method="POST" action="{{ route('staff.mutasi.approve', $mutation->id) }}" class="mb-3">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Setujui mutasi ini? Lokasi aset akan diperbarui otomatis.')"
                            class="w-full py-2.5 bg-green-600 text-white rounded-lg font-semibold text-sm hover:bg-green-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Setujui Mutasi
                    </button>
                </form>
                <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                        class="w-full py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-lg font-semibold text-sm hover:bg-red-100 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak Mutasi
                </button>
            </div>
            @endif

            {{-- Navigasi --}}
            <div class="bg-white rounded-xl shadow p-5 space-y-3">
                <a href="{{ route('staff.mutasi.index') }}"
                   class="w-full py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition flex items-center justify-center gap-2">
                    ← Kembali ke Daftar
                </a>
                <form method="POST" action="{{ route('staff.mutasi.destroy', $mutation->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Hapus data mutasi ini secara permanen?')"
                            class="w-full py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm font-medium hover:bg-red-100 transition">
                        🗑️ Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Penolakan --}}
<div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Tolak Mutasi</h3>
        <p class="text-sm text-gray-500 mb-4">Berikan alasan penolakan yang jelas untuk pengaju.</p>
        <form method="POST" action="{{ route('staff.mutasi.reject', $mutation->id) }}">
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
