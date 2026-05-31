@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Laporan Disposisi Surat')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route(Auth::user()->role . '.laporan.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">📋 Laporan Disposisi Surat</h1>
                <p class="text-gray-600 mt-1">Tracking penugasan surat dinamis menuju proses pengarsipan akhir.</p>
            </div>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route(Auth::user()->role . '.laporan.print-pdf', ['type' => 'disposisi']) }}" target="_blank"
               class="flex items-center gap-2 bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print PDF
            </a>
            <a href="{{ route(Auth::user()->role . '.laporan.export-pdf', ['type' => 'disposisi']) }}"
               class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                PDF + TTE
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route(Auth::user()->role . '.laporan.disposisi') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Periode Waktu</label>
                    <select name="period" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="1month" {{ request('period') == '1month' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                        <option value="3months" {{ request('period') == '3months' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                        <option value="6months" {{ request('period') == '6months' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                        <option value="1year" {{ request('period') == '1year' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Jenis Item</label>
                    <select name="item_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Jenis</option>
                        <option value="arsip" {{ request('item_type') == 'arsip' ? 'selected' : '' }}>Surat / Dokumen</option>
                        <option value="aset" {{ request('item_type') == 'aset' ? 'selected' : '' }}>Aset / Barang</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Status Disposisi</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>MENUNGGU</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>PROSES</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>SELESAI</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i> Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-auto">
                <thead class="bg-gray-50 text-gray-700 uppercase border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-center width-10">No</th>
                        <th class="px-6 py-4">Tgl Disposisi</th>
                        <th class="px-6 py-4">Asal / Sumber</th>
                        <th class="px-6 py-4">Perihal / Deskripsi</th>
                        <th class="px-6 py-4">Prioritas</th>
                        <th class="px-6 py-4">Deadline</th>
                        <th class="px-6 py-4">Diserahkan Ke</th>
                        <th class="px-6 py-4 text-center">Status Kerja</th>
                        <th class="px-6 py-4 text-center">Aksi / Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($dispositions as $index => $disposition)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-center font-medium text-gray-600">
                            {{ $index + ($dispositions->firstItem() ?? 1) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                            {{ $disposition->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $disposition->fromUser->name ?? 'Pimpinan' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="block font-medium text-gray-800">{{ $disposition->disposable->nama ?? $disposition->catatan ?? 'Tidak ada perihal' }}</span>
                            <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">{{ class_basename($disposition->disposable_type) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                @if(in_array($disposition->priority, ['high', 'mendesak', 'penting'])) bg-red-100 text-red-700
                                @elseif($disposition->priority == 'medium') bg-yellow-100 text-yellow-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ strtoupper($disposition->priority ?? 'NORMAL') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                            {{ $disposition->deadline ? \Carbon\Carbon::parse($disposition->deadline)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-700 font-medium">
                            {{ $disposition->toUser->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                @if($disposition->status == 'completed') bg-green-100 text-green-700
                                @elseif($disposition->status == 'in_progress') bg-blue-100 text-blue-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                @if($disposition->status == 'completed') SELESAI
                                @elseif($disposition->status == 'in_progress') PROSES
                                @else MENUNGGU @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm">
                            @if($disposition->status == 'completed' && isset($disposition->disposable->status) && $disposition->disposable->status != 'diarsipkan')
                                <form action="{{ route(Auth::user()->role . '.surat.arsipkan', $disposition->disposable_id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah seluruh tugas disposisi surat ini telah benar-benar selesai dan siap dikunci resmi ke Laci Arsip Statis?')" 
                                            class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                                        <i class="fas fa-box-archive"></i> Selesaikan & Arsipkan
                                    </button>
                                </form>
                            @elseif(isset($disposition->disposable->status) && $disposition->disposable->status == 'diarsipkan')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-md border border-gray-200">
                                    <i class="fas fa-archive text-amber-500"></i> Laci Arsip Resmi
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">Proses Koordinasi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">Tidak ada data disposisi</p>
                                <p class="text-gray-400 text-sm mt-1">Sesuaikan kembali parameter filter pencarian Anda</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dispositions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $dispositions->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection