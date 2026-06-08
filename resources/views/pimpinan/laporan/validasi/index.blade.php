@extends('pimpinan.layouts.app')
@section('title', 'Validasi Laporan & TTE')
@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">✅ Validasi Laporan & TTE</h1>
        <p class="text-sm text-gray-500 mt-0.5">Pengajuan laporan dari admin dan staff yang membutuhkan tanda tangan elektronik</p>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-300 text-green-800 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-300 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    {{-- Statistik --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-400">
            <p class="text-xs text-gray-500">Menunggu Validasi</p>
            <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['menunggu'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500">Sudah Disetujui</p>
            <h3 class="text-2xl font-bold text-green-600">{{ $stats['disetujui'] }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-400">
            <p class="text-xs text-gray-500">Ditolak</p>
            <h3 class="text-2xl font-bold text-red-600">{{ $stats['ditolak'] }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Judul Laporan</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Pengaju</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Diajukan</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pengajuan as $p)
                <tr class="hover:bg-gray-50 transition {{ $p->status === 'menunggu' ? 'bg-yellow-50/40' : '' }}">
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $p->judul }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst(str_replace('-',' ',$p->jenis_laporan)) }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-gray-800 font-medium text-xs">{{ $p->pengaju?->name }}</p>
                        <p class="text-gray-400 text-xs">{{ ucfirst($p->pengaju?->role) }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $p->diajukan_at?->format('d M Y H:i') ?? $p->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        @php $badge = $p->status_badge; @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $badge['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $badge['color'] === 'green'  ? 'bg-green-100 text-green-800'   : '' }}
                            {{ $badge['color'] === 'red'    ? 'bg-red-100 text-red-800'       : '' }}">
                            {{ $badge['text'] }}
                        </span>
                        @if($p->catatan)
                            <p class="text-xs text-red-500 mt-1 italic">{{ $p->catatan }}</p>
                        @endif
                        @if($p->divalidasi_at)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $p->divalidasi_at->format('d M Y H:i') }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2 flex-wrap">
                            {{-- Preview --}}
                            <a href="{{ route('pimpinan.laporan.validasi.preview', $p->id) }}" target="_blank"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Preview
                            </a>

                            @if($p->status === 'menunggu')
                            {{-- Setujui --}}
                            <form method="POST" action="{{ route('pimpinan.laporan.validasi.setujui', $p->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Setujui dan bubuhkan TTE pada laporan ini?')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-semibold hover:bg-green-700 transition">
                                    ✅ Setujui & TTE
                                </button>
                            </form>
                            {{-- Tolak --}}
                            <button type="button"
                                onclick="document.getElementById('modal-{{ $p->id }}').classList.remove('hidden')"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 border border-red-200 rounded-lg text-xs font-semibold hover:bg-red-100 transition">
                                ❌ Tolak
                            </button>

                            @elseif($p->isApproved())
                            {{-- Download dengan TTE --}}
                            <a href="{{ route('pimpinan.laporan.validasi.download', $p->id) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-semibold hover:bg-green-700 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download + TTE
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Modal Tolak --}}
                <div id="modal-{{ $p->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Tolak Pengajuan</h3>
                        <p class="text-sm text-gray-500 mb-3">Laporan: <strong>{{ $p->judul }}</strong></p>
                        <form method="POST" action="{{ route('pimpinan.laporan.validasi.tolak', $p->id) }}">
                            @csrf
                            <textarea name="catatan" rows="3" required placeholder="Tulis alasan penolakan..."
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 mb-4"></textarea>
                            <div class="flex justify-end gap-3">
                                <button type="button"
                                    onclick="document.getElementById('modal-{{ $p->id }}').classList.add('hidden')"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Batal</button>
                                <button type="submit"
                                    class="px-5 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700">
                                    Konfirmasi Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="font-medium">Belum ada pengajuan laporan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($pengajuan->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">{{ $pengajuan->links() }}</div>
        @endif
    </div>
</div>
@endsection
