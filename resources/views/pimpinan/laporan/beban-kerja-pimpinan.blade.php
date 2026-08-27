@extends(Auth::user()->role . '.layouts.app')
@section('title', 'Laporan Beban Kerja Validasi Pimpinan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Beban Kerja Validasi Pimpinan</h1>
            <p class="text-gray-600 mt-1">Jumlah dan waktu proses persetujuan yang ditangani pimpinan — {{ $dateRange['label'] }}</p>
        </div>
        <form method="GET" class="flex gap-2">
            <select name="period" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="1month"  {{ $period === '1month'  ? 'selected' : '' }}>1 Bulan Terakhir</option>
                <option value="3months" {{ $period === '3months' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                <option value="6months" {{ $period === '6months' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                <option value="1year"   {{ $period === '1year'   ? 'selected' : '' }}>1 Tahun Terakhir</option>
            </select>
        </form>
    </div>

    {{-- Statistik Validasi Laporan (TTE) --}}
    <div>
        <h2 class="text-lg font-bold text-gray-700 mb-3">📄 Validasi Laporan (Tanda Tangan Elektronik)</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-blue-500">
                <p class="text-xs font-medium text-gray-500">Total Divalidasi</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $laporanStats['total_divalidasi'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-green-500">
                <p class="text-xs font-medium text-gray-500">Disetujui</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $laporanStats['disetujui'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-red-500">
                <p class="text-xs font-medium text-gray-500">Ditolak</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $laporanStats['ditolak'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-purple-500">
                <p class="text-xs font-medium text-gray-500">Rata-rata Proses</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $laporanStats['rata_rata_jam'] ? number_format($laporanStats['rata_rata_jam'], 1) : '-' }} <span class="text-sm font-normal">jam</span></p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-orange-500">
                <p class="text-xs font-medium text-gray-500">Tercepat / Terlama</p>
                <p class="text-base font-bold text-gray-800 mt-1">
                    {{ $laporanStats['tercepat_jam'] !== null ? number_format($laporanStats['tercepat_jam'], 1) : '-' }} /
                    {{ $laporanStats['terlama_jam'] !== null ? number_format($laporanStats['terlama_jam'], 1) : '-' }} jam
                </p>
            </div>
        </div>
    </div>

    {{-- Breakdown per jenis laporan --}}
    @if($laporanPerJenis->isNotEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="font-semibold text-gray-700 text-sm">Rincian per Jenis Laporan</h3>
        </div>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Jenis Laporan</th>
                    <th class="px-6 py-3 text-center">Jumlah Divalidasi</th>
                    <th class="px-6 py-3 text-center">Rata-rata Waktu Proses (jam)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($laporanPerJenis as $jenis => $data)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ ucfirst($jenis) }}</td>
                    <td class="px-6 py-3 text-center">{{ $data['total'] }}</td>
                    <td class="px-6 py-3 text-center">{{ $data['rata_rata_jam'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Statistik Disposisi --}}
    <div>
        <h2 class="text-lg font-bold text-gray-700 mb-3">📋 Disposisi yang Ditangani Pimpinan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-blue-500">
                <p class="text-xs font-medium text-gray-500">Total Diterima</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $disposisiStats['total_diterima'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-green-500">
                <p class="text-xs font-medium text-gray-500">Total Diselesaikan</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $disposisiStats['total_selesai'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-purple-500">
                <p class="text-xs font-medium text-gray-500">Rata-rata Waktu Selesai</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">{{ $disposisiStats['rata_rata_jam'] }} <span class="text-sm font-normal">jam</span></p>
            </div>
        </div>
    </div>

    {{-- Detail Tabel Validasi Laporan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="font-semibold text-gray-700 text-sm">Detail Validasi Laporan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Judul Laporan</th>
                        <th class="px-6 py-3">Pengaju</th>
                        <th class="px-6 py-3">Diajukan</th>
                        <th class="px-6 py-3">Divalidasi</th>
                        <th class="px-6 py-3 text-center">Waktu Proses</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($laporanValidasi as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $item->judul }}</td>
                        <td class="px-6 py-3">{{ $item->pengaju->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $item->diajukan_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $item->divalidasi_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-3 text-center">{{ $item->waktu_proses_jam }} jam</td>
                        <td class="px-6 py-3 text-center">
                            @if($item->status === 'disetujui')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Disetujui</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada laporan yang divalidasi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection