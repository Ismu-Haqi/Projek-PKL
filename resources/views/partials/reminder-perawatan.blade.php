{{--
    Partial: reminder-perawatan.blade.php
    Dipakai di dashboard admin, staff, dan pimpinan.
    Variabel yang dibutuhkan:
      - $asetJatuhTempoPerawatan : Collection aset H-7
      - $asetPerawatanTerlambat  : Collection aset yang terlambat
--}}

@php
    $totalPerawatan = (isset($asetJatuhTempoPerawatan) ? $asetJatuhTempoPerawatan->count() : 0)
                    + (isset($asetPerawatanTerlambat)  ? $asetPerawatanTerlambat->count()  : 0);
@endphp

@if($totalPerawatan > 0)
<div class="mb-6">

    {{-- Alert terlambat (merah) --}}
    @if(isset($asetPerawatanTerlambat) && $asetPerawatanTerlambat->count() > 0)
    <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-4 mb-3 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-red-800 text-sm">
                    ⚠️ {{ $asetPerawatanTerlambat->count() }} aset melewati jadwal perawatan!
                </p>
                <div class="mt-2 space-y-1">
                    @foreach($asetPerawatanTerlambat->take(3) as $aset)
                    <div class="flex items-center justify-between text-xs bg-red-100 rounded-lg px-3 py-1.5">
                        <span class="font-medium text-red-800">
                            {{ $aset->nama }}
                            <span class="text-red-500 font-normal">({{ $aset->kode_asset }})</span>
                        </span>
                        <span class="text-red-700 font-semibold">
                            Jadwal: {{ \Carbon\Carbon::parse($aset->jadwal_perawatan_selanjutnya)->format('d/m/Y') }}
                            &nbsp;·&nbsp;
                            {{ abs(\Carbon\Carbon::parse($aset->jadwal_perawatan_selanjutnya)->diffInDays(now())) }} hari lalu
                        </span>
                    </div>
                    @endforeach
                    @if($asetPerawatanTerlambat->count() > 3)
                    <p class="text-xs text-red-600 pl-1">+{{ $asetPerawatanTerlambat->count() - 3 }} aset lainnya</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Alert H-7 (kuning) --}}
    @if(isset($asetJatuhTempoPerawatan) && $asetJatuhTempoPerawatan->count() > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-xl p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-yellow-800 text-sm">
                    🔧 {{ $asetJatuhTempoPerawatan->count() }} aset mendekati jadwal perawatan (H-7)
                </p>
                <div class="mt-2 space-y-1">
                    @foreach($asetJatuhTempoPerawatan->take(3) as $aset)
                    @php
                        $sisa = \Carbon\Carbon::now()->startOfDay()
                            ->diffInDays(\Carbon\Carbon::parse($aset->jadwal_perawatan_selanjutnya), false);
                    @endphp
                    <div class="flex items-center justify-between text-xs bg-yellow-100 rounded-lg px-3 py-1.5">
                        <span class="font-medium text-yellow-800">
                            {{ $aset->nama }}
                            <span class="text-yellow-600 font-normal">({{ $aset->kode_asset }})</span>
                            @if($aset->jenis_perawatan)
                            <span class="text-yellow-500"> · {{ $aset->jenis_perawatan }}</span>
                            @endif
                        </span>
                        <span class="text-yellow-700 font-semibold whitespace-nowrap ml-2">
                            @if($sisa == 0)
                                Hari ini!
                            @else
                                {{ $sisa }} hari lagi
                            @endif
                            &nbsp;·&nbsp;
                            {{ \Carbon\Carbon::parse($aset->jadwal_perawatan_selanjutnya)->format('d/m/Y') }}
                        </span>
                    </div>
                    @endforeach
                    @if($asetJatuhTempoPerawatan->count() > 3)
                    <p class="text-xs text-yellow-600 pl-1">+{{ $asetJatuhTempoPerawatan->count() - 3 }} aset lainnya</p>
                    @endif
                </div>
                <div class="mt-2">
                    <a href="{{ route(Auth::user()->role . '.aset.index') }}"
                       class="text-xs text-yellow-700 font-semibold hover:underline">
                        Lihat semua aset →
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endif
