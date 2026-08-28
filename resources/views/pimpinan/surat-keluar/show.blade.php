@extends('pimpinan.layouts.app')

@section('title', 'Detail Surat Keluar')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('pimpinan.surat-keluar.index') }}" class="hover:text-teal-600">Surat Keluar</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">{{ $letter->nomor_agenda }}</span>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">📤 {{ $letter->nomor_agenda }}</h1>
                        <p class="text-gray-400 text-xs mt-1">Diajukan {{ $letter->diajukan_tte_at?->format('d M Y H:i') ?? $letter->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @php $badge = $letter->status_badge; @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $badge['color'] === 'gray'   ? 'bg-gray-100 text-gray-700'   : '' }}
                        {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $badge['color'] === 'green'  ? 'bg-green-100 text-green-800'   : '' }}
                        {{ $badge['color'] === 'red'    ? 'bg-red-100 text-red-800'       : '' }}
                        {{ $badge['color'] === 'blue'   ? 'bg-blue-100 text-blue-800'     : '' }}">
                        {{ $badge['text'] }}
                    </span>
                </div>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Nomor Surat</dt><dd class="font-medium">{{ $letter->nomor_surat ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tanggal Surat</dt><dd class="font-medium">{{ $letter->tanggal_surat->format('d M Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tujuan</dt><dd class="font-medium">{{ $letter->tujuan }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Sifat</dt><dd class="font-medium">{{ ucfirst($letter->sifat) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Diajukan oleh</dt><dd class="font-medium">{{ $letter->pembuat->name ?? '-' }}</dd></div>
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
                    <a href="{{ route('pimpinan.surat-keluar.download', $letter->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition">
                        Unduh Lampiran ({{ $letter->file_size }})
                    </a>
                </div>
                @endif

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('pimpinan.surat-keluar.download-pdf', $letter->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 {{ $letter->status === 'ditandatangani' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-700' }} rounded-lg text-sm hover:opacity-80 transition">
                        {{ $letter->status === 'ditandatangani' ? 'Unduh PDF Surat (dengan TTE)' : 'Unduh PDF Surat' }}
                    </a>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            @if($letter->status === 'menunggu_tte')
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold text-gray-700 mb-4 text-sm">✍️ Tindakan TTE</h3>
                <form method="POST" action="{{ route('pimpinan.surat-keluar.setujui-tte', $letter->id) }}" class="mb-3">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Setujui dan tandatangani surat ini secara elektronik?')"
                            class="w-full py-2.5 bg-green-600 text-white rounded-lg font-semibold text-sm hover:bg-green-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Setujui & Tandatangani (TTE)
                    </button>
                </form>
                <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                        class="w-full py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-lg font-semibold text-sm hover:bg-red-100 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak
                </button>
            </div>
            @endif

            <div class="bg-white rounded-xl shadow p-5">
                <a href="{{ route('pimpinan.surat-keluar.index') }}"
                   class="w-full py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition flex items-center justify-center gap-2">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Tolak Surat Keluar</h3>
        <p class="text-sm text-gray-500 mb-4">Berikan alasan penolakan yang jelas untuk pengaju.</p>
        <form method="POST" action="{{ route('pimpinan.surat-keluar.tolak-tte', $letter->id) }}">
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
