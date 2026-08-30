@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Denah Lokasi Aset')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">🗺️ Denah Lokasi Fisik Aset</h1>
            <p class="text-sm text-gray-500 mt-1">Klik pin untuk melihat detail aset. Berguna untuk verifikasi fisik di lapangan.</p>
        </div>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.denah-aset.kelola') }}"
           class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Kelola Titik Lokasi
        </a>
        @endif
    </div>

    @if(Auth::user()->role === 'admin' && $belumDitempatkanCount > 0)
    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800 flex items-center justify-between">
        <span>⚠️ Ada <strong>{{ $belumDitempatkanCount }}</strong> aset yang belum ditempatkan di denah.</span>
        <a href="{{ route('admin.denah-aset.kelola') }}" class="underline font-medium">Tempatkan sekarang →</a>
    </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-lg shadow p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="kategori" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kategori)
                    <option value="{{ $kategori }}" {{ request('kategori') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                @endforeach
            </select>
            @if(request('kategori') || request('lokasi'))
            <a href="{{ route(Auth::user()->role . '.denah-aset.index') }}" class="text-sm text-gray-500 px-4 py-2 rounded-lg border hover:bg-gray-50 transition">Reset Filter</a>
            @endif
        </form>
    </div>

    {{-- Denah + Pin --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div id="denahWrapper" class="relative w-full overflow-auto" style="max-height: 80vh;">
            <div class="relative inline-block" style="min-width: 100%;">
                <img src="{{ asset('images/denah-kantor.jpg') }}" alt="Denah Kantor" class="block w-full h-auto select-none" draggable="false">

                @foreach($assets as $item)
                <button type="button"
                        class="denah-pin absolute -translate-x-1/2 -translate-y-full group"
                        style="left: {{ $item->posisi_x }}%; top: {{ $item->posisi_y }}%;"
                        data-asset="{{ json_encode(['nama' => $item->nama, 'kode' => $item->kode_asset, 'kategori' => $item->kategori, 'lokasi' => $item->lokasi, 'status' => $item->status, 'id' => $item->id]) }}">
                    <svg class="w-7 h-7 drop-shadow-lg
                        {{ $item->status === 'tersedia'   ? 'text-green-600'  : '' }}
                        {{ $item->status === 'digunakan'  ? 'text-blue-600'   : '' }}
                        {{ $item->status === 'dipinjam'   ? 'text-purple-600' : '' }}
                        {{ $item->status === 'maintenance' ? 'text-yellow-500' : '' }}
                        {{ $item->status === 'rusak'      ? 'text-red-600'    : '' }}
                        {{ $item->status === 'dimusnahkan' ? 'text-gray-400'  : '' }}"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Legenda --}}
    <div class="mt-4 bg-white rounded-lg shadow p-4 flex flex-wrap gap-4 text-xs">
        <span class="font-semibold text-gray-500">Status:</span>
        <span class="flex items-center gap-1.5 text-green-700"><span class="w-3 h-3 rounded-full bg-green-600"></span> Tersedia</span>
        <span class="flex items-center gap-1.5 text-blue-700"><span class="w-3 h-3 rounded-full bg-blue-600"></span> Digunakan</span>
        <span class="flex items-center gap-1.5 text-purple-700"><span class="w-3 h-3 rounded-full bg-purple-600"></span> Dipinjam</span>
        <span class="flex items-center gap-1.5 text-yellow-700"><span class="w-3 h-3 rounded-full bg-yellow-500"></span> Maintenance</span>
        <span class="flex items-center gap-1.5 text-red-700"><span class="w-3 h-3 rounded-full bg-red-600"></span> Rusak</span>
        <span class="flex items-center gap-1.5 text-gray-500"><span class="w-3 h-3 rounded-full bg-gray-400"></span> Dimusnahkan</span>
    </div>

    <p class="text-xs text-gray-400 mt-3">Menampilkan {{ $assets->count() }} aset yang sudah ditempatkan di denah.</p>
</div>

{{-- Modal Detail Aset --}}
<div id="assetModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
        <div class="flex justify-between items-start mb-3">
            <h3 id="modalNama" class="text-lg font-bold text-gray-800"></h3>
            <button onclick="document.getElementById('assetModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Kode Aset</dt><dd id="modalKode" class="font-medium font-mono"></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Kategori</dt><dd id="modalKategori" class="font-medium"></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Lokasi</dt><dd id="modalLokasi" class="font-medium"></dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd id="modalStatus" class="font-medium"></dd></div>
        </dl>
        <a id="modalDetailLink" href="#" class="mt-4 block text-center py-2 bg-teal-600 text-white rounded-lg text-sm font-semibold hover:bg-teal-700 transition">
            Lihat Detail Aset →
        </a>
    </div>
</div>

<script>
document.querySelectorAll('.denah-pin').forEach(function (pin) {
    pin.addEventListener('click', function () {
        const data = JSON.parse(this.getAttribute('data-asset'));
        document.getElementById('modalNama').textContent = data.nama;
        document.getElementById('modalKode').textContent = data.kode || '-';
        document.getElementById('modalKategori').textContent = data.kategori || '-';
        document.getElementById('modalLokasi').textContent = data.lokasi || '-';
        document.getElementById('modalStatus').textContent = data.status;
        document.getElementById('modalDetailLink').href = '{{ route(Auth::user()->role . ".aset.show", ":id") }}'.replace(':id', data.id);
        document.getElementById('assetModal').classList.remove('hidden');
    });
});
</script>
@endsection