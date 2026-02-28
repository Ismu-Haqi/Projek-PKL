@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Laporan Valuasi & Penyusutan Aset')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Penyusutan Aset</h1>
            <p class="text-gray-600 mt-1">Metode Garis Lurus (Straight-Line Method)</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center shadow">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Laporan
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Harga Perolehan (Beli)</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">Rp {{ number_format($totalAsetAwal, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Nilai Buku (Saat Ini)</p>
            <p class="text-3xl font-bold text-green-600 mt-2">Rp {{ number_format($totalNilaiBuku, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-700 uppercase border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Kode & Nama Aset</th>
                        <th class="px-6 py-4">Tgl Beli</th>
                        <th class="px-6 py-4">Harga Beli</th>
                        <th class="px-6 py-4">Umur & Residu</th>
                        <th class="px-6 py-4">Penyusutan/Thn</th>
                        <th class="px-6 py-4">Nilai Saat Ini</th>
                        <th class="px-6 py-4 text-center">Rekomendasi SPK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($assets as $asset)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $asset->kode_asset }}<br>
                            <span class="text-xs text-gray-500">{{ Str::limit($asset->nama, 30) }}</span>
                        </td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($asset->tanggal_pembelian)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-gray-600">Rp {{ number_format($asset->harga_pembelian, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-gray-600 block">{{ $asset->umur_ekonomis }} Tahun</span>
                            <span class="text-xs text-gray-400">Residu: Rp {{ number_format($asset->nilai_residu, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-red-500">-Rp {{ number_format($asset->penyusutan_per_tahun, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($asset->nilai_buku, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($asset->status_kelayakan == 'Layak Pakai')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Layak Pakai</span>
                            @elseif($asset->status_kelayakan == 'Kritis (Perlu Perhatian)')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Kritis</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Waktu Lelang</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Belum ada data aset dengan harga pembelian untuk dihitung penyusutannya.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .space-y-6, .space-y-6 * { visibility: visible; }
        .space-y-6 { position: absolute; left: 0; top: 0; width: 100%; }
        button { display: none !important; }
    }
</style>
@endsection