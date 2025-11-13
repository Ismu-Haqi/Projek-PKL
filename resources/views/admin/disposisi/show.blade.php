@extends(Auth::user()->role . '.layouts.app')

@section('title', 'Detail Disposisi')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route(Auth::user()->role . '.disposisi.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Disposisi</h1>
                <p class="text-gray-600 mt-1">{{ $disposition->nomor_disposisi }}</p>
            </div>
        </div>

        @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'pimpinan' && $disposition->from_user_id === Auth::id()))
        <div class="flex space-x-2">
            <!-- Forward Button untuk Admin (if pending_forward) -->
            @if(Auth::user()->role === 'admin' && $disposition->needsForwarding())
            <button onclick="showForwardModal()" 
               class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
                Teruskan Disposisi
            </button>
            @endif
            
            <a href="{{ route(Auth::user()->role . '.disposisi.edit', $disposition->id) }}" 
               class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Forwarding Status Alert -->
            @if($disposition->needsForwarding())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-bold text-yellow-800">Menunggu Penerusan</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Disposisi ini perlu diteruskan ke <strong>{{ $disposition->finalRecipient->name }}</strong>
                            @if($disposition->forwarding_note)
                            <br>Catatan: {{ $disposition->forwarding_note }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Forwarded Info -->
            @if($disposition->isForwarded())
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-bold text-green-800">Disposisi Telah Diteruskan</h3>
                        <p class="text-sm text-green-700 mt-1">
                            Diteruskan ke <strong>{{ $disposition->forwardedTo->toUser->name }}</strong> 
                            pada {{ $disposition->forwarded_at->format('d M Y, H:i') }}
                            <a href="{{ route(Auth::user()->role . '.disposisi.show', $disposition->forwardedTo->id) }}" 
                               class="underline ml-2">Lihat Detail</a>
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Original Disposition (if forwarded) -->
            @if($disposition->isForwardedDisposition())
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-bold text-blue-800">Disposisi Terusan</h3>
                        <p class="text-sm text-blue-700 mt-1">
                            Diteruskan dari <strong>{{ $disposition->forwardedFrom->fromUser->name }}</strong>
                            <a href="{{ route(Auth::user()->role . '.disposisi.show', $disposition->forwardedFrom->id) }}" 
                               class="underline ml-2">Lihat Disposisi Asli</a>
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Disposition Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Informasi Disposisi</h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Subject -->
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Subjek</label>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $disposition->subject }}</p>
                    </div>

                    <!-- Item Reference -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">
                                Referensi {{ $disposition->item_type }}
                            </label>
                            @if($disposition->item_type === 'Arsip')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Arsip/Surat
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    Aset Inventaris
                                </span>
                            @endif
                        </div>
                        
                        @if($disposition->item)
                            <div class="space-y-2">
                                <div>
                                    <p class="text-xs text-gray-500">
                                        {{ $disposition->item_type === 'Arsip' ? 'Nomor Surat' : 'Kode Aset' }}
                                    </p>
                                    <p class="font-semibold text-gray-800 font-mono">{{ $disposition->item_identifier }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">
                                        {{ $disposition->item_type === 'Arsip' ? 'Judul' : 'Nama Aset' }}
                                    </p>
                                    <p class="font-semibold text-gray-800">{{ $disposition->item_name }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-gray-200">
                                @if($disposition->item_type === 'Arsip')
                                    <a href="{{ route(Auth::user()->role . '.arsip.show', $disposition->item->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors text-sm font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Detail Arsip
                                    </a>
                                @else
                                    <a href="{{ route(Auth::user()->role . '.aset.show', $disposition->item->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-pink-100 text-pink-700 rounded-lg hover:bg-pink-200 transition-colors text-sm font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Detail Aset
                                    </a>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">Item tidak ditemukan</p>
                        @endif
                    </div>

                    <!-- Instruction -->
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Instruksi</label>
                        <div class="mt-2 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $disposition->instruction }}</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($disposition->notes)
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Catatan Penerima</label>
                        <div class="mt-2 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $disposition->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Completion Proof -->
                    @if($disposition->hasCompletionProof())
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-lg p-4 border border-green-200">
                        <h3 class="text-sm font-bold text-green-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            Bukti Penyelesaian
                        </h3>
                        
                        @if($disposition->completion_description)
                        <div class="mb-3">
                            <label class="text-xs font-semibold text-gray-600 uppercase">Deskripsi Hasil</label>
                            <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $disposition->completion_description }}</p>
                        </div>
                        @endif
                        
                        @if($disposition->completion_file)
                        <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-green-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded bg-green-100 flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $disposition->completion_file_name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $disposition->completion_file_extension }} • {{ $disposition->completion_file_size }} KB
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route(Auth::user()->role . '.disposisi.downloadCompletion', $disposition->id) }}" 
                               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Update Status Form (untuk PENERIMA disposisi: Admin, Staff, Pimpinan) -->
            @if($disposition->to_user_id === Auth::id() && 
                $disposition->status !== 'completed' && 
                $disposition->status !== 'rejected')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Update Status & Upload Bukti Penyelesaian</h2>
                </div>
                
                <form action="{{ route(Auth::user()->role . '.disposisi.updateStatus', $disposition->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      class="p-6 space-y-4"
                      x-data="{ status: '{{ $disposition->status }}', showProof: false }">
                    @csrf
                    @method('PUT')

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" 
                                x-model="status"
                                @change="showProof = (status === 'completed')"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="in_progress" {{ $disposition->status == 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="completed">Selesai</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>

                    <!-- Completion Proof (show when completed) -->
                    <div x-show="status === 'completed'" 
                         x-cloak
                         class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                        <div class="flex mb-3">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                            </svg>
                            <p class="text-sm text-yellow-700 font-semibold">
                                Wajib upload file bukti ATAU berikan deskripsi hasil pekerjaan!
                            </p>
                        </div>
                        
                        <!-- File Upload -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Penyelesaian</label>
                            <input type="file" 
                                   name="completion_file" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX, JPG, PNG, ZIP (Max: 10MB)</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Hasil Pekerjaan</label>
                            <textarea name="completion_description" 
                                      rows="4" 
                                      placeholder="Jelaskan hasil pekerjaan yang telah diselesaikan..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                        </div>
                    </div>

                    @error('completion_proof')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan/Feedback</label>
                        <textarea name="notes" 
                                  rows="4" 
                                  placeholder="Berikan catatan atau feedback..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">{{ $disposition->notes }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 transition-all shadow-lg font-medium flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Status
                    </button>
                </form>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Timeline Disposisi
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Created -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Disposisi Dibuat</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $disposition->created_at->format('d M Y, H:i') }} WIB</p>
                                <p class="text-sm text-gray-500 mt-1">Oleh: {{ $disposition->fromUser->name }}</p>
                            </div>
                        </div>

                        @if($disposition->read_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Disposisi Dibaca</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $disposition->read_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        @endif

                        @if($disposition->forwarded_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"/>
                                    <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Disposisi Diteruskan</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $disposition->forwarded_at->format('d M Y, H:i') }} WIB</p>
                                <p class="text-sm text-gray-500 mt-1">Ke: {{ $disposition->forwardedTo->toUser->name }}</p>
                            </div>
                        </div>
                        @endif

                        @if($disposition->status === 'in_progress' || $disposition->status === 'completed')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Sedang Diproses</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $disposition->updated_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        @endif

                        @if($disposition->completed_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Disposisi Selesai</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $disposition->completed_at->format('d M Y, H:i') }} WIB</p>
                                @if($disposition->hasCompletionProof())
                                <p class="text-sm text-green-600 font-medium mt-1">✓ Dengan Bukti Penyelesaian</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($disposition->status === 'rejected')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Disposisi Ditolak</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $disposition->updated_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800">Status Disposisi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Status</label>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'in_progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'completed' => 'bg-green-100 text-green-700 border-green-200',
                                'rejected' => 'bg-red-100 text-red-700 border-red-200'
                            ];
                        @endphp
                        <div class="mt-2">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold border {{ $statusColors[$disposition->status] }}">
                                {{ $disposition->statusLabel['text'] }}
                            </span>
                        </div>
                    </div>

                    @if($disposition->forwarding_status !== 'direct')
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Status Penerusan</label>
                        @php
                            $forwardingColors = [
                                'pending_forward' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'forwarded' => 'bg-green-100 text-green-700 border-green-200',
                            ];
                        @endphp
                        <div class="mt-2">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold border {{ $forwardingColors[$disposition->forwarding_status] }}">
                                {{ $disposition->forwardingStatusLabel['text'] }}
                            </span>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Prioritas</label>
                        @php
                            $priorityColors = [
                                'urgent' => 'bg-red-100 text-red-700 border-red-200',
                                'high' => 'bg-orange-100 text-orange-700 border-orange-200',
                                'normal' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'low' => 'bg-gray-100 text-gray-700 border-gray-200'
                            ];
                        @endphp
                        <div class="mt-2">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold border {{ $priorityColors[$disposition->priority] }}">
                                @if($disposition->priority === 'urgent')
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                @endif
                                {{ $disposition->priorityLabel['text'] }}
                            </span>
                        </div>
                    </div>

                    @if($disposition->deadline)
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Batas Waktu</label>
                        <div class="mt-2 p-3 {{ $disposition->isOverdue() ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg">
                            <p class="font-bold text-gray-800">{{ $disposition->deadline->format('d M Y') }}</p>
                            @if($disposition->isOverdue())
                                <p class="text-xs text-red-600 font-semibold mt-1">⚠️ Terlambat!</p>
                            @elseif($disposition->days_until_deadline !== null && $disposition->days_until_deadline >= 0)
                                <p class="text-xs text-gray-600 mt-1">{{ $disposition->days_until_deadline }} hari lagi</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- People Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800">Informasi Pihak</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Pengirim</label>
                        <div class="mt-2 flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold mr-3">
                                {{ strtoupper(substr($disposition->fromUser->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $disposition->fromUser->name }}</p>
                                <p class="text-xs text-gray-500">{{ $disposition->fromUser->unit ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Penerima</label>
                        <div class="mt-2 flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold mr-3">
                                {{ strtoupper(substr($disposition->toUser->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $disposition->toUser->name }}</p>
                                <p class="text-xs text-gray-500">{{ $disposition->toUser->unit ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($disposition->finalRecipient && $disposition->forwarding_status !== 'direct')
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Penerima Akhir</label>
                        <div class="mt-2 flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white font-bold mr-3">
                                {{ strtoupper(substr($disposition->finalRecipient->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $disposition->finalRecipient->name }}</p>
                                <p class="text-xs text-gray-500">{{ $disposition->finalRecipient->unit ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-2">
                @if($disposition->item_type === 'Arsip' && $disposition->item)
                    <a href="{{ route(Auth::user()->role . '.arsip.show', $disposition->item->id) }}" 
                       class="block w-full px-4 py-2 bg-white text-center rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700 border border-gray-200">
                        📄 Lihat Arsip Terkait
                    </a>
                @elseif($disposition->item_type === 'Aset' && $disposition->item)
                    <a href="{{ route(Auth::user()->role . '.aset.show', $disposition->item->id) }}" 
                       class="block w-full px-4 py-2 bg-white text-center rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700 border border-gray-200">
                        📦 Lihat Detail Aset
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Forward Modal (Admin only) -->
@if(Auth::user()->role === 'admin' && $disposition->needsForwarding())
<div id="forwardModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-xl">
            <h3 class="text-xl font-bold text-white">Teruskan Disposisi</h3>
        </div>
        
        <form action="{{ route('admin.disposisi.forward', $disposition->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <p class="text-sm text-blue-700">
                    Disposisi akan diteruskan ke:<br>
                    <strong class="text-blue-900">{{ $disposition->finalRecipient->name }}</strong>
                    ({{ $disposition->finalRecipient->unit ?? '-' }})
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Penerusan (Opsional)</label>
                <textarea name="forwarding_note" 
                          rows="4" 
                          placeholder="Tambahkan catatan khusus untuk penerusan disposisi..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $disposition->forwarding_note }}</textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="hideForwardModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all font-medium">
                    Teruskan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
function showForwardModal() {
    document.getElementById('forwardModal')?.classList.remove('hidden');
}

function hideForwardModal() {
    document.getElementById('forwardModal')?.classList.add('hidden');
}

document.getElementById('forwardModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideForwardModal();
    }
});
</script>

<style>
[x-cloak] { 
    display: none !important; 
}
</style>
@endsection