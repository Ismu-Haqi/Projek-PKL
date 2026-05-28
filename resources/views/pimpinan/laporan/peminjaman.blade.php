@extends(Auth::user()->role . '.layouts.app')
@section('title', 'Laporan Peminjaman Aset')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Peminjaman Aset</h1>
            <p class="text-gray-600 mt-1">Riwayat sirkulasi dan status peminjaman aset inventaris.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route(Auth::user()->role . '.laporan.print-pdf', ['type' => 'peminjaman', 'period' => $period]) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center shadow">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-500">Total Pengajuan</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-yellow-500">
            <p class="text-sm font-medium text-gray-500">Sedang Dipinjam</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['dipinjam'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-500">Telah Dikembalikan</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['dikembalikan'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-red-500">
            <p class="text-sm font-medium text-gray-500">Ditolak / Terlambat</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['ditolak'] + $stats['terlambat'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase">
                    <tr>
                        <th class="px-6 py-4">Tgl Pengajuan</th>
                        <th class="px-6 py-4">Nama Aset</th>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Tgl Kembali</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($borrows as $borrow)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $borrow->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $borrow->asset->nama ?? 'Aset Dihapus' }}</td>
                        <td class="px-6 py-4">{{ $borrow->borrower->name ?? 'User Dihapus' }}</td>
                        <td class="px-6 py-4">{{ $borrow->tanggal_kembali ? \Carbon\Carbon::parse($borrow->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusLabels = [
                                    'pending' => 'MENUNGGU',
                                    'approved' => 'DISETUJUI',
                                    'borrowed' => 'DIPINJAM',
                                    'returned' => 'DIKEMBALIKAN',
                                    'rejected' => 'DITOLAK',
                                    'overdue' => 'TERLAMBAT'
                                ];
                                $labelText = $statusLabels[$borrow->status] ?? strtoupper($borrow->status);
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if(in_array($borrow->status, ['returned', 'approved'])) bg-green-100 text-green-700
                                @elseif($borrow->status == 'borrowed') bg-blue-100 text-blue-700
                                @elseif(in_array($borrow->status, ['rejected', 'overdue'])) bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ $labelText }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data peminjaman aset di periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection