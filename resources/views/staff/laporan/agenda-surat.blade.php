@extends(Auth::user()->role . '.layouts.app')
@section('title', 'Laporan Rekap Agenda Surat')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Rekap Agenda Surat</h1>
            <p class="text-gray-600 mt-1">Buku agenda gabungan surat masuk dan surat keluar secara kronologis.</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            @include('partials.laporan-buttons', ['type' => 'agenda-surat'])
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-gray-500">
            <p class="text-sm font-medium text-gray-500">Total Agenda</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-500">Surat Masuk</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['total_masuk'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-teal-500">
            <p class="text-sm font-medium text-gray-500">Surat Keluar</p>
            <p class="text-2xl font-bold text-teal-600 mt-1">{{ $stats['total_keluar'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase">
                    <tr>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Nomor Agenda</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Dari / Kepada</th>
                        <th class="px-6 py-4">Perihal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($agenda as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($item->jenis === 'masuk')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">MASUK</span>
                            @else
                                <span class="px-2 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-bold">KELUAR</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-800">{{ $item->nomor_agenda }}</td>
                        <td class="px-6 py-4">{{ optional($item->tanggal)->format('d M Y') }}</td>
                        <td class="px-6 py-4">{{ $item->pihak }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ \Illuminate\Support\Str::limit($item->perihal, 50) }}</td>
                        <td class="px-6 py-4 text-center text-xs text-gray-500">{{ ucwords(str_replace('_', ' ', $item->status)) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada data agenda surat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
