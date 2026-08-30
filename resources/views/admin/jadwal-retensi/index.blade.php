@extends('admin.layouts.app')

@section('title', 'Jadwal Retensi Arsip')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📚 Jadwal Retensi Arsip (JRA)</h1>
            <p class="text-sm text-gray-500 mt-1">Aturan klasifikasi arsip: masa simpan aktif, inaktif, dan nasib akhir, sesuai kaidah kearsipan resmi.</p>
        </div>
        <a href="{{ route('admin.jadwal-retensi.create') }}"
           class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Klasifikasi
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Klasifikasi</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Aktif</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Inaktif</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Total Simpan</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Nasib Akhir</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Dipakai</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($daftar as $jra)
                <tr class="hover:bg-gray-50 transition {{ !$jra->aktif ? 'opacity-50' : '' }}">
                    <td class="px-4 py-3 font-mono font-semibold text-teal-700">{{ $jra->kode_klasifikasi }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-800">{{ $jra->nama_klasifikasi }}</div>
                        @if($jra->deskripsi)
                        <div class="text-xs text-gray-400">{{ \Illuminate\Support\Str::limit($jra->deskripsi, 60) }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">{{ $jra->jangka_aktif_tahun }} tahun</td>
                    <td class="px-4 py-3 text-center">{{ $jra->jangka_inaktif_tahun }} tahun</td>
                    <td class="px-4 py-3 text-center font-semibold">{{ $jra->total_masa_simpan }} tahun</td>
                    <td class="px-4 py-3">
                        @php $label = $jra->nasib_akhir_label; @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $label['color'] === 'red'    ? 'bg-red-100 text-red-800'       : '' }}
                            {{ $label['color'] === 'blue'   ? 'bg-blue-100 text-blue-800'     : '' }}
                            {{ $label['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ $label['text'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center text-gray-500">{{ $jra->arsip_count }} arsip</td>
                    <td class="px-4 py-3 text-center">
                        @if($jra->aktif)
                            <span class="text-green-600 text-xs font-semibold">Aktif</span>
                        @else
                            <span class="text-gray-400 text-xs font-semibold">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.jadwal-retensi.edit', $jra->id) }}"
                               class="text-yellow-600 hover:text-yellow-800 p-1" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.jadwal-retensi.destroy', $jra->id) }}" onsubmit="return confirm('Hapus klasifikasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-4 py-12 text-center text-gray-400">Belum ada klasifikasi retensi arsip.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4 text-xs text-blue-800">
        💡 Saat kamu memilih klasifikasi ini di form Tambah/Edit Arsip, sistem otomatis menghitung <strong>Tanggal Retensi</strong> arsip = Tanggal Arsip + Masa Aktif (tahun). Pengingat otomatis akan tetap mengikuti tanggal ini sesuai fitur pengingat retensi yang sudah ada.
    </div>
</div>
@endsection
