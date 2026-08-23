@extends('admin.layouts.app')

@section('title', 'Galeri Dashboard')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">🖼️ Galeri Dokumentasi Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Gambar di sini akan tampil di bagian paling bawah dashboard semua role (admin, staff, pimpinan)</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Form Upload --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h3 class="font-semibold text-gray-700 mb-4 text-sm">➕ Tambah Gambar Baru</h3>
        <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar <span class="text-red-500">*</span></label>
                <input type="file" name="gambar" accept="image/jpeg,image/jpg,image/png,image/webp" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <p class="mt-1 text-xs text-gray-400">JPG, PNG, atau WEBP. Maksimal 5MB.</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Judul</label>
                    <input type="text" name="judul" placeholder="cth: Kegiatan Penataan Arsip 2026"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Singkat</label>
                    <input type="text" name="deskripsi" placeholder="Penjelasan singkat foto ini..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white rounded-lg text-sm font-semibold hover:bg-teal-700 transition shadow">
                    Unggah Gambar
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Gambar --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4 text-sm">📋 Daftar Gambar Galeri ({{ $images->count() }})</h3>

        @if($images->isEmpty())
        <div class="text-center py-12 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="font-medium">Belum ada gambar di galeri</p>
            <p class="text-sm mt-1">Unggah gambar pertama menggunakan form di atas</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($images as $image)
            <div class="border border-gray-200 rounded-xl overflow-hidden {{ !$image->aktif ? 'opacity-50' : '' }}">
                <img src="{{ $image->gambar_url }}" alt="{{ $image->judul }}" class="w-full h-40 object-cover">
                <form method="POST" action="{{ route('admin.galeri.update', $image->id) }}" class="p-4 space-y-3">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Judul</label>
                        <input type="text" name="judul" value="{{ $image->judul }}"
                               class="w-full border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500">{{ $image->deskripsi }}</textarea>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <label class="block text-xs font-medium text-gray-500">Urutan</label>
                            <input type="number" name="urutan" value="{{ $image->urutan }}" min="0"
                                   class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <label class="flex items-center gap-1.5 text-xs text-gray-600 cursor-pointer">
                            <input type="checkbox" name="aktif" value="1" {{ $image->aktif ? 'checked' : '' }}
                                   class="rounded text-teal-600 focus:ring-teal-500">
                            Tampilkan
                        </label>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="flex-1 py-1.5 bg-teal-50 text-teal-700 border border-teal-200 rounded-lg text-xs font-medium hover:bg-teal-100 transition">
                            Simpan
                        </button>
                    </div>
                </form>
                <form method="POST" action="{{ route('admin.galeri.destroy', $image->id) }}" class="px-4 pb-4">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Hapus gambar ini dari galeri?')"
                            class="w-full py-1.5 bg-red-50 text-red-700 border border-red-200 rounded-lg text-xs font-medium hover:bg-red-100 transition">
                        Hapus Gambar
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
