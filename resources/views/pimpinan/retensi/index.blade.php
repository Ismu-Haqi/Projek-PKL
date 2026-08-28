@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Jadwal Retensi Arsip')

@section('content')
<div class="p-6 space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📜 Jadwal Retensi Arsip (JRA)</h1>
            <p class="text-gray-600 mt-1">Aturan resmi masa aktif, masa inaktif, dan nasib akhir arsip per kategori, sesuai kaidah kearsipan.</p>
        </div>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.retensi.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg flex items-center transition duration-200 transform hover:scale-105 whitespace-nowrap">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Aturan JRA
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">{{ session('info') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    <!-- Peringatan kategori belum diatur -->
    @if($kategoriBelumDiatur->count())
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <p class="text-sm text-yellow-800">
            <strong>⚠️ {{ $kategoriBelumDiatur->count() }} kategori arsip</strong> belum memiliki aturan JRA:
            {{ $kategoriBelumDiatur->pluck('name')->join(', ') }}.
            @if(Auth::user()->role === 'admin')
                Arsip dari kategori ini masih memakai tanggal retensi manual.
            @endif
        </p>
    </div>
    @endif

    <!-- Tabel Aturan JRA -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase">
                    <tr>
                        <th class="px-6 py-3">Kategori Arsip</th>
                        <th class="px-6 py-3">Kode Klasifikasi</th>
                        <th class="px-6 py-3">Masa Aktif</th>
                        <th class="px-6 py-3">Masa Inaktif</th>
                        <th class="px-6 py-3">Total Retensi</th>
                        <th class="px-6 py-3">Nasib Akhir</th>
                        <th class="px-6 py-3">Status</th>
                        @if(Auth::user()->role === 'admin')
                        <th class="px-6 py-3 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($schedules as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $s->category->name ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $s->kode_klasifikasi ?: '-' }}</td>
                        <td class="px-6 py-3">{{ $s->retensi_aktif_tahun }} tahun</td>
                        <td class="px-6 py-3">{{ $s->retensi_inaktif_tahun }} tahun</td>
                        <td class="px-6 py-3 font-semibold">{{ $s->total_retensi_tahun }} tahun</td>
                        <td class="px-6 py-3">
                            @php
                                $nasibColor = match($s->nasib_akhir) {
                                    'permanen' => 'blue',
                                    'dinilai_kembali' => 'yellow',
                                    default => 'red',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-{{ $nasibColor }}-100 text-{{ $nasibColor }}-700">
                                {{ \App\Models\RetentionSchedule::labelNasibAkhir($s->nasib_akhir) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            @if($s->is_active)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Nonaktif</span>
                            @endif
                        </td>
                        @if(Auth::user()->role === 'admin')
                        <td class="px-6 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.retensi.edit', $s->id) }}" class="text-blue-600 hover:text-blue-800 font-medium mr-3">Edit</a>
                            <form action="{{ route('admin.retensi.destroy', $s->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus aturan JRA untuk kategori {{ $s->category->name ?? '' }}?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role === 'admin' ? 8 : 7 }}" class="px-6 py-8 text-center text-gray-500">
                            Belum ada aturan Jadwal Retensi Arsip yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-xs text-gray-500">
        <strong>Keterangan:</strong> Masa Aktif dihitung sejak tanggal arsip dibuat. Setelah masa aktif berakhir, arsip
        masuk masa Inaktif. Total Retensi = Masa Aktif + Masa Inaktif — setelah itu arsip mengikuti Nasib Akhir sesuai
        aturan ini (dimusnahkan, disimpan permanen, atau dinilai kembali).
    </div>
</div>
@endsection
