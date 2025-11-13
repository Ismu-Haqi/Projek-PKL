@extends('pimpinan.layouts.app')

@section('title', 'Edit Disposisi')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ itemType: '{{ old('item_type', $disposition->item_type === 'Arsip' ? 'arsip' : 'aset') }}' }">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('pimpinan.disposisi.show', $disposition->id) }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Edit Disposisi</h1>
                <p class="text-gray-600 mt-1">{{ $disposition->nomor_disposisi }}</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Formulir Disposisi
            </h2>
        </div>

        <form action="{{ route('pimpinan.disposisi.update', $disposition->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Select Item Type -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tipe Item <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-purple-500" 
                           :class="itemType === 'arsip' ? 'border-purple-500 bg-purple-50' : 'border-gray-300'"
                           @click="itemType = 'arsip'">
                        <input type="radio" name="item_type" value="arsip" class="sr-only" 
                               :checked="itemType === 'arsip'">
                        <div class="flex items-center w-full">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Arsip/Surat</p>
                                <p class="text-xs text-gray-500">Disposisi dokumen arsip</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-pink-500"
                           :class="itemType === 'aset' ? 'border-pink-500 bg-pink-50' : 'border-gray-300'"
                           @click="itemType = 'aset'">
                        <input type="radio" name="item_type" value="aset" class="sr-only"
                               :checked="itemType === 'aset'">
                        <div class="flex items-center w-full">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Aset</p>
                                <p class="text-xs text-gray-500">Disposisi inventaris aset</p>
                            </div>
                        </div>
                    </label>
                </div>
                @error('item_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Select Item (Dynamic) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span x-text="itemType === 'aset' ? 'Pilih Aset' : 'Pilih Arsip/Surat'"></span>
                    <span class="text-red-500">*</span>
                </label>
                
                <!-- Arsip Select -->
                <div x-show="itemType === 'arsip'" x-cloak>
                    <select name="item_id" id="item_select_arsip" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('item_id') border-red-500 @enderror">
                        <option value="">-- Pilih Arsip/Surat --</option>
                        @foreach($archives as $archive)
                        <option value="{{ $archive->id }}" 
                                {{ old('item_id', $disposition->disposable_id) == $archive->id && $disposition->item_type === 'Arsip' ? 'selected' : '' }}>
                            {{ $archive->nomor_surat }} - {{ $archive->judul }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Aset Select -->
                <div x-show="itemType === 'aset'" x-cloak>
                    <select name="item_id" id="item_select_aset" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('item_id') border-red-500 @enderror">
                        <option value="">-- Pilih Aset --</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" 
                                {{ old('item_id', $disposition->disposable_id) == $asset->id && $disposition->item_type === 'Aset' ? 'selected' : '' }}>
                            {{ $asset->kode_asset }} - {{ $asset->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                @error('item_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Recipient -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Penerima Disposisi <span class="text-red-500">*</span>
                </label>
                <select name="to_user_id" id="to_user_id" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('to_user_id') border-red-500 @enderror">
                    <option value="">-- Pilih Penerima --</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ (old('to_user_id', $disposition->to_user_id) == $user->id) ? 'selected' : '' }}>
                        {{ $user->name }} - {{ $user->unit ?? 'Tidak ada unit' }}
                    </option>
                    @endforeach
                </select>
                @error('to_user_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Subjek Disposisi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" id="subject" required
                    value="{{ old('subject', $disposition->subject) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('subject') border-red-500 @enderror">
                @error('subject')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority & Deadline -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Priority -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Prioritas <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" id="priority" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('priority') border-red-500 @enderror">
                        <option value="normal" {{ old('priority', $disposition->priority) == 'normal' ? 'selected' : '' }}>Biasa</option>
                        <option value="low" {{ old('priority', $disposition->priority) == 'low' ? 'selected' : '' }}>Tidak Mendesak</option>
                        <option value="high" {{ old('priority', $disposition->priority) == 'high' ? 'selected' : '' }}>Mendesak</option>
                        <option value="urgent" {{ old('priority', $disposition->priority) == 'urgent' ? 'selected' : '' }}>Sangat Mendesak</option>
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deadline -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Batas Waktu
                    </label>
                    <input type="date" name="deadline" id="deadline"
                        value="{{ old('deadline', $disposition->deadline ? $disposition->deadline->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('deadline') border-red-500 @enderror">
                    @error('deadline')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Instruction -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Instruksi/Perintah <span class="text-red-500">*</span>
                </label>
                <textarea name="instruction" id="instruction" rows="6" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('instruction') border-red-500 @enderror">{{ old('instruction', $disposition->instruction) }}</textarea>
                @error('instruction')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warning Box -->
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200 p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h4 class="text-sm font-bold text-yellow-800 mb-1">⚠️ Perhatian</h4>
                        <p class="text-sm text-yellow-700">Perubahan pada disposisi akan mempengaruhi tracking dan status yang sudah ada. Pastikan perubahan yang Anda lakukan sudah benar.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('pimpinan.disposisi.show', $disposition->id) }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white rounded-lg hover:from-yellow-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
[x-cloak] { 
    display: none !important; 
}
</style>
@endsection