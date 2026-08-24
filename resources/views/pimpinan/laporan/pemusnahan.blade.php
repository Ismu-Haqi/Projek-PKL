@extends(Auth::user()->role . '.layouts.app')
@section('title', 'Laporan Pemusnahan Aset')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Pemusnahan Aset</h1>
            <p class="text-gray-600 mt-1">Rekap seluruh usulan pemusnahan aset beserta status persetujuannya.</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            @include('partials.laporan-buttons', ['type' => 'pemusnahan'])
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-gray-500">
            <p class="text-sm font-medium text-gray-500">Total Usulan</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-yellow-500">
            <p class="text-sm font-medium text-gray-500">Menunggu Persetujuan</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['menunggu'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-500">Disetujui</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['disetujui'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-red-600">
            <p class="text-sm font-medium text-gray-500">Ditolak</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['ditolak'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase">
                    <tr>
                        <th class="px-6 py-4">No. Usulan</th>
                        <th class="px-6 py-4">Aset</th>
                        <th class="px-6 py-4">Diajukan Oleh</th>
                        <th class="px-6 py-4">Tanggal Usulan</th>
                        <th class="px-6 py-4">Alasan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Disetujui Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($destructions as $d)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><strong class="text-gray-900 font-mono text-xs">{{ $d->nomor_pemusnahan }}</strong></td>
                        <td class="px-6 py-4">
                            {{ $d->asset->nama ?? '-' }}<br>
                            <span class="text-xs text-gray-400">{{ $d->asset->kode_asset ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">{{ $d->pengaju->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ optional($d->tanggal_usulan)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ \Illuminate\Support\Str::limit($d->alasan_pemusnahan, 60) }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($d->status === 'disetujui')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase">Disetujui</span>
                            @elseif($d->status === 'ditolak')
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold uppercase">Ditolak</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold uppercase">Menunggu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $d->penyetuju->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">Belum ada data usulan pemusnahan aset.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
