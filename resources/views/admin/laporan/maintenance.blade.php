@extends(Auth::user()->role . '.layouts.app')
@section('title', 'Laporan Pemeliharaan & Kerusakan Aset')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Pemeliharaan Aset</h1>
            <p class="text-gray-600 mt-1">Daftar aset instansi yang mengalami kerusakan atau sedang dalam pemeliharaan.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route(Auth::user()->role . '.laporan.print-pdf', ['type' => 'maintenance']) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center shadow">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-gray-500">
            <p class="text-sm font-medium text-gray-500">Total Aset Bermasalah</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-yellow-500">
            <p class="text-sm font-medium text-gray-500">Sedang Pemeliharaan</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['sedang_maintenance'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-orange-500">
            <p class="text-sm font-medium text-gray-500">Kondisi Kurang Baik</p>
            <p class="text-2xl font-bold text-orange-600 mt-1">{{ $stats['perlu_perbaikan'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-red-600">
            <p class="text-sm font-medium text-gray-500">Kondisi Rusak Berat</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['rusak_berat'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase">
                    <tr>
                        <th class="px-6 py-4">Kode & Nama Aset</th>
                        <th class="px-6 py-4">Lokasi / Unit</th>
                        <th class="px-6 py-4">Penanggung Jawab</th>
                        <th class="px-6 py-4 text-center">Kondisi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($assets as $asset)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <strong class="text-gray-900">{{ $asset->kode_asset }}</strong><br>
                            <span class="text-gray-500">{{ $asset->nama }}</span>
                        </td>
                        <td class="px-6 py-4">{{ $asset->lokasi ?? '-' }}<br><span class="text-xs text-gray-400">{{ $asset->unit ?? '-' }}</span></td>
                        <td class="px-6 py-4">{{ $asset->penanggung_jawab ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($asset->kondisi == 'rusak')
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold uppercase">RUSAK BERAT</span>
                            @else
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-bold uppercase">KURANG BAIK</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($asset->status == 'maintenance')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold uppercase">PEMELIHARAAN</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold uppercase">RUSAK</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Luar biasa! Tidak ada data aset yang rusak atau dalam pemeliharaan saat ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection