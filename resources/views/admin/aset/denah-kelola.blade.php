@extends('admin.layouts.app')

@section('title', 'Kelola Denah Lokasi Aset')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📍 Kelola Titik Lokasi Aset</h1>
            <p class="text-sm text-gray-500 mt-1">Pilih aset di daftar, lalu klik titik lokasinya pada denah.</p>
        </div>
        <a href="{{ route('admin.denah-aset.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-5 rounded-lg transition">
            ← Lihat Tampilan Denah
        </a>
    </div>

    <div id="toast" class="hidden fixed top-6 right-6 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium"></div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Daftar Aset --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow p-4 sticky top-4">
                <div class="flex gap-2 mb-3">
                    <a href="{{ route('admin.denah-aset.kelola', ['filter' => 'belum']) }}"
                       class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold transition {{ $filter === 'belum' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-600' }}">
                        Belum Ditempatkan
                    </a>
                    <a href="{{ route('admin.denah-aset.kelola', ['filter' => 'sudah']) }}"
                       class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold transition {{ $filter === 'sudah' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                        Sudah Ditempatkan
                    </a>
                    <a href="{{ route('admin.denah-aset.kelola', ['filter' => 'semua']) }}"
                       class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold transition {{ $filter === 'semua' ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-600' }}">
                        Semua
                    </a>
                </div>

                <div id="assetList" class="space-y-1.5 max-h-[65vh] overflow-y-auto">
                    @forelse($assets as $item)
                    <button type="button"
                            class="asset-pick w-full text-left p-2.5 rounded-lg border text-xs transition
                                {{ $item->sudah_di_petakan ? 'border-green-200 bg-green-50' : 'border-gray-200 hover:bg-teal-50 hover:border-teal-300' }}"
                            data-id="{{ $item->id }}"
                            data-nama="{{ $item->nama }}"
                            data-kode="{{ $item->kode_asset }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $item->nama }}</p>
                                <p class="text-gray-400 font-mono">{{ $item->kode_asset }}</p>
                                <p class="text-gray-400">{{ $item->lokasi ?? 'Belum ada lokasi teks' }}</p>
                            </div>
                            @if($item->sudah_di_petakan)
                            <span class="text-green-600">✓</span>
                            @endif
                        </div>
                    </button>
                    @empty
                    <p class="text-center text-gray-400 text-sm py-8">Tidak ada aset di filter ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Denah --}}
        <div class="lg:col-span-2">
            <div id="pickModeInfo" class="hidden mb-3 p-3 bg-teal-50 border border-teal-200 rounded-lg text-sm text-teal-800 flex items-center justify-between">
                <span>📍 Klik pada denah untuk menempatkan: <strong id="pickModeNama"></strong></span>
                <button type="button" id="cancelPick" class="text-xs underline">Batal</button>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="relative w-full overflow-auto" style="max-height: 80vh;">
                    <div class="relative inline-block" style="min-width: 100%;">
                        <img id="denahImage" src="{{ asset('images/denah-kantor.jpg') }}" alt="Denah Kantor"
                             class="block w-full h-auto select-none" draggable="false">

                        @foreach($assets as $item)
                        @if($item->sudah_di_petakan)
                        <div class="denah-pin-existing absolute -translate-x-1/2 -translate-y-full"
                             id="pin-{{ $item->id }}"
                             style="left: {{ $item->posisi_x }}%; top: {{ $item->posisi_y }}%;"
                             title="{{ $item->nama }}">
                            <svg class="w-6 h-6 text-teal-600 drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            <button type="button" class="hapus-pin absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-white text-[9px] flex items-center justify-center hover:bg-red-600" data-id="{{ $item->id }}" title="Hapus posisi">✕</button>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-400 mt-3">
                💡 Tips: Klik salah satu aset di daftar kiri (terutama yang belum ditempatkan), lalu klik lokasinya di denah. Untuk hapus titik, klik tombol ✕ merah di pin.
            </p>
        </div>
    </div>
</div>

<script>
let selectedAssetId = null;
let selectedAssetNama = null;
const denahImage = document.getElementById('denahImage');
const pickModeInfo = document.getElementById('pickModeInfo');
const pickModeNama = document.getElementById('pickModeNama');

function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'fixed top-6 right-6 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium ' + (isError ? 'bg-red-500' : 'bg-green-500');
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

document.querySelectorAll('.asset-pick').forEach(function (btn) {
    btn.addEventListener('click', function () {
        selectedAssetId = this.dataset.id;
        selectedAssetNama = this.dataset.nama;
        pickModeNama.textContent = selectedAssetNama;
        pickModeInfo.classList.remove('hidden');
        denahImage.style.cursor = 'crosshair';
    });
});

document.getElementById('cancelPick').addEventListener('click', function () {
    selectedAssetId = null;
    pickModeInfo.classList.add('hidden');
    denahImage.style.cursor = 'default';
});

denahImage.addEventListener('click', function (e) {
    if (!selectedAssetId) {
        showToast('Pilih aset dari daftar kiri terlebih dahulu.', true);
        return;
    }

    const rect = denahImage.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;

    fetch(`{{ url('admin/denah-aset') }}/${selectedAssetId}/posisi`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ posisi_x: x.toFixed(2), posisi_y: y.toFixed(2) }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            setTimeout(() => window.location.reload(), 700);
        } else {
            showToast('Gagal menyimpan posisi.', true);
        }
    })
    .catch(() => showToast('Terjadi kesalahan jaringan.', true));
});

document.querySelectorAll('.hapus-pin').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        if (!confirm('Hapus titik lokasi aset ini dari denah?')) return;

        const id = this.dataset.id;
        fetch(`{{ url('admin/denah-aset') }}/${id}/posisi`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.message);
            setTimeout(() => window.location.reload(), 700);
        });
    });
});
</script>
@endsection
