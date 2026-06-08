@extends('admin.layouts.app')
@section('title', 'Pengajuan Laporan')
@section('content')
<div class="p-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.laporan.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📋 Pengajuan Laporan ke Pimpinan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Laporan yang diajukan untuk divalidasi & ditandatangani TTE oleh pimpinan</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-300 text-green-800 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('warning'))
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg">⚠️ {{ session('warning') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-300 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Judul Laporan</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Diajukan</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Divalidasi oleh</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pengajuan as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $p->judul }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst(str_replace('-',' ',$p->jenis_laporan)) }}</p>
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
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $p->validator?->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($p->isApproved() && $p->canDownload(Auth::id()))
                            <a href="{{ route('admin.laporan.pengajuan.download', $p->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-semibold hover:bg-green-700 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download PDF + TTE
                            </a>
                        @elseif($p->status === 'ditolak')
                            <form method="POST" action="{{ route('admin.laporan.pengajuan.ajukan-ulang', $p->id) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition">
                                    🔄 Ajukan Ulang
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400 italic">Menunggu validasi pimpinan...</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="font-medium">Belum ada pengajuan laporan</p>
                        <p class="text-sm mt-1">Ajukan laporan dari halaman laporan masing-masing</p>
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
