@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.aset.index') }}" class="hover:text-blue-600">Manajemen Aset</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Edit Aset</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">✏️ Edit Aset</h1>
        <p class="text-gray-600 mt-2">{{ $asset->nama }} - {{ $asset->kode_asset }}</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.aset.update', $asset->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')
        
        <!-- Informasi Dasar -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-blue-500">
                📋 Informasi Dasar
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Aset -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Aset <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama', $asset->nama) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror">
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kategori" value="{{ old('kategori', $asset->kategori) }}" required list="kategoris"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kategori') border-red-500 @enderror">
                    <datalist id="kategoris">
                        @foreach($categories as $category)
                            <option value="{{ $category }}">
                        @endforeach
                        <option value="Komputer">
                        <option value="Printer">
                        <option value="Proyektor">
                    </datalist>
                    @error('kategori')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Merk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Merk</label>
                    <input type="text" name="merk" value="{{ old('merk', $asset->merk) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Tipe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe/Model</label>
                    <input type="text" name="tipe" value="{{ old('tipe', $asset->tipe) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Serial Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Spesifikasi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi</label>
                    <textarea name="spesifikasi" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('spesifikasi', $asset->spesifikasi) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Status & Kondisi -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-green-500">
                🔧 Status & Kondisi
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kondisi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kondisi <span class="text-red-500">*</span>
                    </label>
                    <select name="kondisi" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="baik" {{ old('kondisi', $asset->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="cukup" {{ old('kondisi', $asset->kondisi) == 'cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="kurang" {{ old('kondisi', $asset->kondisi) == 'kurang' ? 'selected' : '' }}>Kurang</option>
                        <option value="rusak" {{ old('kondisi', $asset->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="tersedia" {{ old('status', $asset->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="digunakan" {{ old('status', $asset->status) == 'digunakan' ? 'selected' : '' }}>Digunakan</option>
                        <option value="diperbaiki" {{ old('status', $asset->status) == 'diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
                        <option value="rusak" {{ old('status', $asset->status) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Lokasi & Penempatan -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-purple-500">
                📍 Lokasi & Penempatan
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Lokasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $asset->lokasi) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja</label>
                    <input type="text" name="unit" value="{{ old('unit', $asset->unit) }}" list="units"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <datalist id="units">
                        @foreach($units as $unit)
                            <option value="{{ $unit }}">
                        @endforeach
                    </datalist>
                </div>

                <!-- Penanggung Jawab -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab', $asset->penanggung_jawab) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Informasi Pembelian & Garansi -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-orange-500">
                💰 Pembelian & Garansi
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tanggal Pembelian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembelian</label>
                    <input type="date" name="tanggal_pembelian" 
                           value="{{ old('tanggal_pembelian', $asset->tanggal_pembelian ? $asset->tanggal_pembelian->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Harga Pembelian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Pembelian (Rp)</label>
                    <input type="number" name="harga_pembelian" value="{{ old('harga_pembelian', $asset->harga_pembelian) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Masa Garansi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Masa Garansi (Bulan)</label>
                    <input type="number" name="masa_garansi" value="{{ old('masa_garansi', $asset->masa_garansi) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Foto & Keterangan -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-pink-500">
                📸 Foto & Keterangan
            </h2>
            
            <div class="grid grid-cols-1 gap-4">
                <!-- Foto Lama -->
                @if($asset->foto)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                    <img src="{{ asset('storage/' . $asset->foto) }}" alt="Foto Aset" class="max-w-xs rounded-lg shadow-md">
                </div>
                @endif

                <!-- Foto Baru -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $asset->foto ? 'Ganti Foto' : 'Upload Foto' }}
                    </label>
                    <input type="file" name="foto" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onchange="previewImage(event)">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                    
                    <!-- Preview -->
                    <div id="imagePreview" class="mt-3 hidden">
                        <img src="" alt="Preview" class="max-w-xs rounded-lg shadow-md">
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan', $asset->keterangan) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center pt-6 border-t">
            <!-- Tombol Hapus (Link ke form terpisah) -->
            <a href="javascript:void(0)" 
               onclick="confirmDelete()"
               class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition inline-flex items-center">
                🗑️ Hapus Aset
            </a>

            <!-- Tombol Batal & Update -->
            <div class="flex gap-3">
                <a href="{{ route('admin.aset.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                    💾 Update Aset
                </button>
            </div>
        </div>
    </form>

    <!-- Form Delete (Terpisah & Hidden) - FIXED: Pastikan di luar form update -->
    <form id="deleteForm" action="{{ route('admin.aset.destroy', $asset->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const img = preview.querySelector('img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

function confirmDelete() {
    if (confirm('Yakin ingin menghapus aset "{{ $asset->nama }}"?\n\nData yang dihapus tidak dapat dikembalikan!')) {
        // Debug log
        console.log('Submitting delete form...');
        
        const form = document.getElementById('deleteForm');
        if (form) {
            form.submit();
        } else {
            console.error('Form delete tidak ditemukan!');
        }
    }
}
</script>
@endsection