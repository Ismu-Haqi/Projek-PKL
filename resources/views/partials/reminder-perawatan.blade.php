{{--
    Partial: reminder-perawatan.blade.php
    Dipakai di dashboard admin, staff, dan pimpinan.
    Variabel yang dibutuhkan:
      - $asetJatuhTempoPerawatan : Collection aset H-7
      - $asetPerawatanTerlambat  : Collection aset yang terlambat

    Semua item digabung jadi satu strip yang bergulir otomatis ke kiri
    (gaya sama seperti galeri dokumentasi di bawah dashboard), berhenti
    saat cursor/sentuhan berada di atasnya, supaya semua notif perawatan
    bisa terlihat tanpa harus dipotong "+X lainnya".
--}}

@php
    $totalPerawatan = (isset($asetJatuhTempoPerawatan) ? $asetJatuhTempoPerawatan->count() : 0)
                    + (isset($asetPerawatanTerlambat)  ? $asetPerawatanTerlambat->count()  : 0);
@endphp

@if($totalPerawatan > 0)
<div class="mb-6">
    <div class="flex items-center gap-2 mb-3">
        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <h2 class="text-base font-bold text-gray-800">Notifikasi Perawatan Aset</h2>
        <span class="text-xs bg-gray-100 text-gray-600 font-semibold px-2 py-0.5 rounded-full">{{ $totalPerawatan }}</span>
    </div>

    <div id="perawatanScroll" class="perawatan-scroll">
        @php
            $itemsTerlambat  = isset($asetPerawatanTerlambat)  ? $asetPerawatanTerlambat  : collect();
            $itemsJatuhTempo = isset($asetJatuhTempoPerawatan) ? $asetJatuhTempoPerawatan : collect();
        @endphp

        {{-- Digandakan 2x supaya efek bergulir tak berujung terasa mulus (sama seperti galeri) --}}
        @for($rep = 0; $rep < 2; $rep++)

            @foreach($itemsTerlambat as $aset)
            <div class="perawatan-card perawatan-card-danger">
                <div class="perawatan-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="perawatan-card-body">
                    <p class="perawatan-card-status">Terlambat</p>
                    <p class="perawatan-card-name">{{ $aset->nama }}</p>
                    <p class="perawatan-card-code">{{ $aset->kode_asset }}</p>
                    <p class="perawatan-card-date">
                        {{ \Carbon\Carbon::parse($aset->jadwal_perawatan_selanjutnya)->format('d/m/Y') }}
                        &nbsp;·&nbsp;
                        {{ abs(\Carbon\Carbon::parse($aset->jadwal_perawatan_selanjutnya)->diffInDays(now())) }} hari lalu
                    </p>
                </div>
            </div>
            @endforeach

            @foreach($itemsJatuhTempo as $aset)
            @php
                $sisa = \Carbon\Carbon::now()->startOfDay()
                    ->diffInDays(\Carbon\Carbon::parse($aset->jadwal_perawatan_selanjutnya), false);
            @endphp
            <div class="perawatan-card perawatan-card-warning">
                <div class="perawatan-card-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="perawatan-card-body">
                    <p class="perawatan-card-status">{{ $sisa == 0 ? 'Hari ini' : $sisa . ' hari lagi' }}</p>
                    <p class="perawatan-card-name">{{ $aset->nama }}</p>
                    <p class="perawatan-card-code">
                        {{ $aset->kode_asset }}
                        @if($aset->jenis_perawatan) · {{ $aset->jenis_perawatan }} @endif
                    </p>
                    <p class="perawatan-card-date">{{ \Carbon\Carbon::parse($aset->jadwal_perawatan_selanjutnya)->format('d/m/Y') }}</p>
                </div>
            </div>
            @endforeach

        @endfor
    </div>
</div>

<style>
    .perawatan-scroll {
        display: flex;
        gap: 14px;
        overflow-x: hidden;
        overflow-y: visible;
        padding: 4px 4px 10px 4px;
        scrollbar-width: none;
        cursor: default;
    }
    .perawatan-scroll::-webkit-scrollbar { display: none; }

    .perawatan-card {
        flex: 0 0 auto;
        width: 230px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border-radius: 12px;
        padding: 12px 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .perawatan-card:hover {
        transform: scale(1.05);
        z-index: 5;
        box-shadow: 0 10px 22px rgba(0,0,0,0.15);
    }

    .perawatan-card-danger  { background: #fef2f2; border-left: 4px solid #ef4444; }
    .perawatan-card-warning { background: #fffbeb; border-left: 4px solid #f59e0b; }

    .perawatan-card-icon { flex-shrink: 0; margin-top: 2px; }
    .perawatan-card-icon svg { width: 20px; height: 20px; }
    .perawatan-card-danger  .perawatan-card-icon svg { color: #ef4444; }
    .perawatan-card-warning .perawatan-card-icon svg { color: #f59e0b; }

    .perawatan-card-body { min-width: 0; flex: 1; }
    .perawatan-card-status {
        font-size: 11px;
        font-weight: 700;
        margin: 0 0 2px 0;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .perawatan-card-danger  .perawatan-card-status { color: #b91c1c; }
    .perawatan-card-warning .perawatan-card-status { color: #b45309; }

    .perawatan-card-name {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .perawatan-card-code {
        font-size: 11px;
        color: #6b7280;
        margin: 1px 0 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .perawatan-card-date {
        font-size: 11px;
        font-weight: 600;
        margin: 4px 0 0 0;
    }
    .perawatan-card-danger  .perawatan-card-date { color: #b91c1c; }
    .perawatan-card-warning .perawatan-card-date { color: #b45309; }

    @media (max-width: 640px) {
        .perawatan-card { width: 190px; }
    }
</style>

<script>
(function () {
    const track = document.getElementById('perawatanScroll');
    if (!track) return;

    let isPaused    = false;
    let scrollSpeed = 0.6;
    let halfWidth   = 0;

    function calcHalfWidth() {
        halfWidth = track.scrollWidth / 2;
    }
    calcHalfWidth();
    window.addEventListener('resize', calcHalfWidth);

    function step() {
        if (!isPaused) {
            track.scrollLeft += scrollSpeed;
            if (track.scrollLeft >= halfWidth) {
                track.scrollLeft -= halfWidth;
            }
        }
        requestAnimationFrame(step);
    }
    requestAnimationFrame(step);

    track.addEventListener('mouseenter', () => { isPaused = true; });
    track.addEventListener('mouseleave', () => { isPaused = false; });
    track.addEventListener('touchstart', () => { isPaused = true; }, { passive: true });
    track.addEventListener('touchend',   () => { isPaused = false; }, { passive: true });
})();
</script>
@endif
