@extends('pimpinan.layouts.app')

@section('title', 'Input Surat Masuk')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    {{-- Header --}}
    <div class="flex items-center mb-6">
        <a href="{{ route('pimpinan.surat-masuk.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Input Surat Masuk</h1>
            <p class="text-gray-500 mt-1">Isi formulir data surat masuk yang diterima</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex items-center">
            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h2 class="text-xl font-bold text-white">Formulir Surat Masuk</h2>
            <span class="ml-auto text-blue-100 text-sm font-mono">{{ $nomorAgenda }}</span>
        </div>

        <form action="{{ route('pimpinan.surat-masuk.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Nomor Surat & Tanggal Surat --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nomor Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}"
                           placeholder="Contoh: 100/DKP/V/2026"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nomor_surat') border-red-400 @enderror">
                    @error('nomor_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Tanggal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_surat') border-red-400 @enderror">
                    @error('tanggal_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Tanggal Diterima & Sifat --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Tanggal Diterima <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_diterima') border-red-400 @enderror">
                    @error('tanggal_diterima')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Sifat Surat <span class="text-red-500">*</span>
                    </label>
                    <select name="sifat" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('sifat') border-red-400 @enderror">
                        <option value="biasa"         {{ old('sifat') === 'biasa'         ? 'selected' : '' }}>Biasa</option>
                        <option value="segera"        {{ old('sifat') === 'segera'        ? 'selected' : '' }}>Segera</option>
                        <option value="sangat_segera" {{ old('sifat') === 'sangat_segera' ? 'selected' : '' }}>Sangat Segera</option>
                        <option value="rahasia"       {{ old('sifat') === 'rahasia'       ? 'selected' : '' }}>Rahasia</option>
                    </select>
                    @error('sifat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Pengirim --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Pengirim <span class="text-red-500">*</span>
                </label>
                <input type="text" name="pengirim" value="{{ old('pengirim') }}"
                       placeholder="Nama instansi atau orang pengirim surat"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('pengirim') border-red-400 @enderror">
                @error('pengirim')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Perihal --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Perihal / Pokok Surat <span class="text-red-500">*</span>
                </label>
                <input type="text" name="perihal" value="{{ old('perihal') }}"
                       placeholder="Perihal surat secara singkat"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('perihal') border-red-400 @enderror">
                @error('perihal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Kategori & Unit Tujuan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori Surat</label>
                    <input type="text" name="kategori" value="{{ old('kategori') }}"
                           placeholder="Undangan, Permohonan, Pemberitahuan, dll"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Unit/Bidang Tujuan</label>
                    <input type="text" name="unit_tujuan" value="{{ old('unit_tujuan') }}"
                           placeholder="Bidang yang menjadi tujuan surat"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="3"
                          placeholder="Catatan atau informasi tambahan (opsional)"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 resize-none">{{ old('keterangan') }}</textarea>
            </div>

            {{-- Upload File --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Upload Scan Surat
                    <span class="text-gray-400 font-normal">(PDF / JPG / PNG, maks 10MB)</span>
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition cursor-pointer"
                     onclick="document.getElementById('file_surat').click()">
                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm text-gray-500" id="file-label">Klik untuk pilih file atau drag & drop</p>
                    <input type="file" id="file_surat" name="file_surat" class="hidden" accept=".pdf,.jpg,.jpeg,.png"
                           onchange="document.getElementById('file-label').textContent = this.files[0]?.name ?? 'Klik untuk pilih file'">
                </div>
                @error('file_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('pimpinan.surat-masuk.index') }}"
                   class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition shadow-sm">
                    Simpan Surat Masuk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection