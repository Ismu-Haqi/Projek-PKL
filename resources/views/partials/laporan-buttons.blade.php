{{--
    Partial: _laporan-buttons.blade.php
    Props:
    - $type       : jenis laporan (arsip, disposisi, aset, dll)
    - $showAjukan : true/false apakah role ini bisa ajukan ke pimpinan (default: true)
    - $extraParams: array parameter tambahan (optional)
--}}

@php
    $role        = Auth::user()->role;
    $params      = array_merge(['type' => $type], request()->query(), $extraParams ?? []);
    $showAjukan  = $showAjukan ?? ($role !== 'pimpinan');
    $bisa_ajukan = in_array($type, \App\Models\LaporanPengajuan::jenisYangBisaDiajukan());

    // Cek status pengajuan terbaru user ini untuk jenis laporan ini
    $pengajuanTerakhir = $showAjukan ? \App\Models\LaporanPengajuan::where('diajukan_oleh', Auth::id())
        ->where('jenis_laporan', $type)
        ->orderBy('created_at', 'desc')
        ->first() : null;
@endphp

<div class="flex flex-wrap gap-2 items-center">

    {{-- Tombol Print PDF — tanpa TTE, langsung buka --}}
    <a href="{{ route($role . '.laporan.print-pdf', $params) }}" target="_blank"
       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print PDF
    </a>

    @if($showAjukan && $bisa_ajukan)

        @if($pengajuanTerakhir && $pengajuanTerakhir->status === 'menunggu')
            {{-- Sedang menunggu validasi --}}
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg text-sm font-medium cursor-not-allowed">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                Menunggu Validasi Pimpinan
            </span>

        @elseif($pengajuanTerakhir && $pengajuanTerakhir->status === 'disetujui' && $pengajuanTerakhir->canDownload(Auth::id()))
            {{-- Sudah disetujui — tombol download TTE --}}
            <a href="{{ route($role . '.laporan.pengajuan.download', $pengajuanTerakhir->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download PDF + TTE
            </a>

            {{-- Ajukan lagi untuk filter berbeda --}}
            <form method="POST" action="{{ route($role . '.laporan.pengajuan.ajukan') }}" class="inline">
                @csrf
                <input type="hidden" name="jenis_laporan" value="{{ $type }}">
                @foreach($params as $key => $val)
                    @if($key !== 'type')
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <button type="submit"
                    onclick="return confirm('Ajukan laporan ini ke pimpinan untuk mendapat TTE baru?')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                    📋 Ajukan TTE Baru
                </button>
            </form>

        @elseif($pengajuanTerakhir && $pengajuanTerakhir->status === 'ditolak')
            {{-- Ditolak — bisa ajukan ulang --}}
            <form method="POST" action="{{ route($role . '.laporan.pengajuan.ajukan-ulang', $pengajuanTerakhir->id) }}" class="inline">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 border border-red-300 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                    🔄 Ajukan Ulang ke Pimpinan
                </button>
            </form>
            <span class="text-xs text-red-500 italic">Ditolak: {{ $pengajuanTerakhir->catatan }}</span>

        @else
            {{-- Belum pernah diajukan — tombol ajukan --}}
            <form method="POST" action="{{ route($role . '.laporan.pengajuan.ajukan') }}" class="inline">
                @csrf
                <input type="hidden" name="jenis_laporan" value="{{ $type }}">
                @foreach($params as $key => $val)
                    @if($key !== 'type')
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <button type="submit"
                    onclick="return confirm('Ajukan laporan ini ke pimpinan untuk mendapat TTE?')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Ajukan ke Pimpinan (TTE)
                </button>
            </form>
        @endif

        {{-- Link ke daftar pengajuan --}}
        <a href="{{ route($role . '.laporan.pengajuan.index') }}"
           class="text-xs text-gray-500 hover:text-blue-600 underline transition">
            Lihat status pengajuan →
        </a>

    @endif
</div>
