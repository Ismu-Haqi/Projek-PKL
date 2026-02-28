@extends('pimpinan.layouts.app')

@section('title', 'Buat Disposisi Baru')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ itemType: 'arsip', needsForwarding: false, showFinalRecipient: false }">
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('pimpinan.disposisi.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Buat Disposisi Baru</h1>
                <p class="text-gray-600 mt-1">Isi formulir untuk membuat disposisi surat atau aset</p>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-4 mb-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h4 class="text-sm font-bold text-blue-800 mb-1">Informasi Disposisi</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Anda dapat mengirim disposisi <strong>langsung ke Admin</strong></li>
                    <li>• Jika ingin mengirim ke Staff, disposisi akan <strong>diteruskan melalui Admin</strong></li>
                    <li>• Admin akan meneruskan disposisi Anda ke penerima yang dituju</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Formulir Disposisi
            </h2>
        </div>

        <form action="{{ route('pimpinan.disposisi.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

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

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span x-text="itemType === 'aset' ? 'Pilih Aset' : 'Pilih Arsip/Surat'"></span>
                    <span class="text-red-500">*</span>
                </label>
                
                <div x-show="itemType === 'arsip'" x-cloak>
                    <select name="item_id" 
                            :required="itemType === 'arsip'"
                            :disabled="itemType !== 'arsip'"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Pilih Arsip/Surat --</option>
                        @foreach($archives as $archive)
                        <option value="{{ $archive->id }}">{{ $archive->nomor_surat }} - {{ $archive->judul }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="itemType === 'aset'" x-cloak>
                    <select name="item_id" 
                            :required="itemType === 'aset'"
                            :disabled="itemType !== 'aset'"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <option value="">-- Pilih Aset --</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">{{ $asset->kode_asset }} - {{ $asset->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Penerima Disposisi <span class="text-red-500">*</span>
                </label>
                
                <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               x-model="needsForwarding"
                               @change="showFinalRecipient = needsForwarding"
                               class="form-checkbox h-5 w-5 text-purple-600 rounded border-gray-300 focus:ring-2 focus:ring-purple-500">
                        <span class="ml-3 text-sm font-medium text-gray-700">
                            Kirim ke Staff (melalui Admin)
                        </span>
                    </label>
                    <p class="text-xs text-gray-500 ml-8 mt-1">
                        Jika dicentang, disposisi akan diteruskan Admin ke staff yang Anda pilih
                    </p>
                </div>

                <input type="hidden" name="to_user_id" value="{{ $users->first()->id }}">

                <div x-show="showFinalRecipient" x-cloak>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Penerima Akhir (Staff) <span class="text-red-500">*</span>
                    </label>
                    <select name="final_recipient_id"
                            :required="needsForwarding"
                            :disabled="!needsForwarding"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Pilih Staff --</option>
                        @php
                            $allUsers = \App\Models\User::where('role', 'staff')->get();
                        @endphp
                        @foreach($allUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->unit ?? '-' }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Disposisi akan dikirim ke Admin terlebih dahulu, lalu Admin akan meneruskannya ke staff ini
                    </p>
                </div>

                <div x-show="!showFinalRecipient" x-cloak>
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-700">
                            📨 Disposisi akan dikirim langsung ke <strong>Admin</strong>
                        </p>
                    </div>
                </div>
            </div>

            <div x-show="showFinalRecipient" x-cloak>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Catatan untuk Admin (Penerusan)
                </label>
                <textarea name="forwarding_note" 
                          :disabled="!needsForwarding"
                          rows="3" 
                          placeholder="Contoh: Mohon diteruskan dengan segera, terkait proyek X..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                <p class="text-xs text-gray-500 mt-1">Catatan khusus untuk Admin mengenai penerusan disposisi ini</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Subjek Disposisi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" id="subject" required
                    placeholder="Contoh: Tindak Lanjut Surat Edaran"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Prioritas <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="normal">Biasa</option>
                        <option value="low">Tidak Mendesak</option>
                        <option value="high">Mendesak</option>
                        <option value="urgent">Sangat Mendesak</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Batas Waktu
                    </label>
                    <input type="date" name="deadline"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <p class="text-xs text-gray-500 mt-1">Opsional</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Instruksi/Perintah <span class="text-red-500">*</span>
                </label>
                <textarea name="instruction" rows="6" required
                    placeholder="Tuliskan instruksi atau perintah disposisi secara detail..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200 p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h4 class="text-sm font-bold text-blue-800 mb-1">Informasi Penting</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Penerima akan mendapat notifikasi setelah disposisi dibuat</li>
                            <li>• Nomor disposisi akan dibuat otomatis oleh sistem</li>
                            <li>• Status awal disposisi adalah "Menunggu"</li>
                            <li>• Jika dikirim melalui Admin, disposisi akan berstatus "Menunggu Penerusan"</li>
                            <li>• Anda dapat memantau status penerusan di halaman detail disposisi</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('pimpinan.disposisi.index') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Disposisi
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>[x-cloak] { display: none !important; }</style>
@endsection