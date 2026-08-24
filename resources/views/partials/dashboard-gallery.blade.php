{{-- ============================================================
     Galeri Dokumentasi Dashboard
     Tampil di bagian paling bawah dashboard semua role.
     Gambar bergulir OTOMATIS ke kiri terus-menerus, BERHENTI saat
     cursor disentuhkan ke galeri, dan membesar saat hover per-gambar,
     dengan judul + deskripsi singkat di bawah tiap gambar.
============================================================ --}}
@php
    $dashboardGalleryImages = \App\Models\DashboardGallery::aktif()->urut()->get();
@endphp

@if($dashboardGalleryImages->isNotEmpty())
<div class="mt-8 mb-2">
    <div class="flex items-center gap-2 mb-4">
        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <h2 class="text-lg font-bold text-gray-800">Dokumentasi Kegiatan</h2>
    </div>

    <div id="gandariaGalleryScroll" class="gandaria-gallery-scroll">
        {{-- Gambar digandakan 2x supaya efek bergulir tak berujung terasa mulus --}}
        @for($rep = 0; $rep < 2; $rep++)
            @foreach($dashboardGalleryImages as $img)
            <div class="gandaria-gallery-item">
                <div class="gandaria-gallery-image-wrap">
                    <img src="{{ $img->gambar_url }}" alt="{{ $img->judul ?? 'Dokumentasi' }}" loading="lazy" draggable="false">
                </div>
                @if($img->judul || $img->deskripsi)
                <div class="gandaria-gallery-caption">
                    @if($img->judul)
                        <p class="gandaria-gallery-title">{{ $img->judul }}</p>
                    @endif
                    @if($img->deskripsi)
                        <p class="gandaria-gallery-desc">{{ $img->deskripsi }}</p>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        @endfor
    </div>
</div>

<style>
    .gandaria-gallery-scroll {
        display: flex;
        gap: 18px;
        overflow-x: hidden; /* scroll dikontrol via JS, bukan drag manual */
        overflow-y: visible;
        padding: 10px 4px 20px 4px;
        scrollbar-width: none;
        cursor: default;
    }
    .gandaria-gallery-scroll::-webkit-scrollbar { display: none; }

    .gandaria-gallery-item {
        flex: 0 0 auto;
        width: 240px;
        transition: transform 0.3s ease;
    }
    .gandaria-gallery-image-wrap {
        width: 100%;
        height: 160px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        position: relative;
        z-index: 1;
        transition: box-shadow 0.3s ease;
    }
    .gandaria-gallery-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.35s ease;
        user-select: none;
    }

    /* Hover: gambar membesar, muncul di atas item lain, dan otomatis berhenti bergulir (lihat JS) */
    .gandaria-gallery-item:hover {
        transform: scale(1.08);
        z-index: 10;
    }
    .gandaria-gallery-item:hover .gandaria-gallery-image-wrap {
        box-shadow: 0 12px 28px rgba(0,0,0,0.25);
    }
    .gandaria-gallery-item:hover .gandaria-gallery-image-wrap img {
        transform: scale(1.1);
    }

    .gandaria-gallery-caption {
        margin-top: 8px;
        padding: 0 2px;
    }
    .gandaria-gallery-title {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 2px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .gandaria-gallery-desc {
        font-size: 11px;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 640px) {
        .gandaria-gallery-item { width: 180px; }
        .gandaria-gallery-image-wrap { height: 120px; }
    }
</style>

<script>
(function () {
    const track = document.getElementById('gandariaGalleryScroll');
    if (!track) return;

    let isPaused   = false;
    let scrollSpeed = 0.6; // px per frame, atur di sini kalau mau lebih cepat/lambat
    let halfWidth   = 0;

    function calcHalfWidth() {
        // Karena gambar digandakan 2x, separuh dari scrollWidth = panjang 1 set asli
        halfWidth = track.scrollWidth / 2;
    }
    calcHalfWidth();
    window.addEventListener('resize', calcHalfWidth);

    function step() {
        if (!isPaused) {
            track.scrollLeft += scrollSpeed;
            // Kalau sudah melewati satu set penuh, reset ke awal secara mulus (efek tak berujung)
            if (track.scrollLeft >= halfWidth) {
                track.scrollLeft -= halfWidth;
            }
        }
        requestAnimationFrame(step);
    }
    requestAnimationFrame(step);

    // Berhenti saat cursor menyentuh galeri, lanjut lagi saat cursor keluar
    track.addEventListener('mouseenter', () => { isPaused = true; });
    track.addEventListener('mouseleave', () => { isPaused = false; });

    // Untuk layar sentuh (tablet/hp): berhenti saat disentuh, lanjut saat jari dilepas
    track.addEventListener('touchstart', () => { isPaused = true; }, { passive: true });
    track.addEventListener('touchend',   () => { isPaused = false; }, { passive: true });
})();
</script>
@endif