@extends('admin.layouts.app')

@section('title', 'Disposisi Menunggu Penerusan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center mr-3 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
                Disposisi Menunggu Penerusan
            </h1>
            <p class="text-gray-600 mt-1 ml-15">Disposisi dari Staff/Pimpinan yang perlu diteruskan</p>
        </div>
        
        <a href="{{ route('admin.disposisi.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Disposisi
        </a>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-4">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h4 class="text-sm font-bold text-blue-800 mb-1">Informasi</h4>
                <p class="text-sm text-blue-700">
                    Ketika Staff atau Pimpinan membuat disposisi untuk pihak lain (bukan Admin), 
                    disposisi tersebut akan masuk ke halaman ini untuk Anda teruskan ke penerima yang dituju.
                </p>
            </div>
        </div>
    </div>

    <!-- Dispositions List -->
    @if($dispositions->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12">
            <div class="text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Disposisi Menunggu</h3>
                <p class="text-gray-600">Semua disposisi sudah diteruskan atau tidak ada yang perlu penerusan</p>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($dispositions as $disposition)
            <div class="bg-white rounded-xl shadow-sm border-2 border-yellow-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <!-- Left Side -->
                        <div class="flex-1">
                            <!-- Header -->
                            <div class="flex items-center mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200 mr-3">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    Menunggu Penerusan
                                </span>
                                <span class="font-mono text-sm font-bold text-gray-700">{{ $disposition->nomor_disposisi }}</span>
                            </div>

                            <!-- Subject -->
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $disposition->subject }}</h3>

                            <!-- Meta Info -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <!-- From -->
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Dari</p>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold text-xs mr-2">
                                            {{ strtoupper(substr($disposition->fromUser->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">{{ $disposition->fromUser->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $disposition->fromUser->role }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- To (Final Recipient) -->
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Tujuan Akhir</p>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white font-bold text-xs mr-2">
                                            {{ strtoupper(substr($disposition->finalRecipient->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">{{ $disposition->finalRecipient->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $disposition->finalRecipient->unit ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Item -->
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Item</p>
                                    <div class="flex items-center">
                                        @if($disposition->item_type === 'Arsip')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-700 mr-2">
                                                📄 Arsip
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-pink-100 text-pink-700 mr-2">
                                                📦 Aset
                                            </span>
                                        @endif
                                        <p class="text-sm text-gray-700 font-medium">{{ $disposition->item_identifier }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Instruction Preview -->
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Instruksi</p>
                                <p class="text-sm text-gray-700">{{ Str::limit($disposition->instruction, 150) }}</p>
                            </div>

                            <!-- Forwarding Note (if any) -->
                            @if($disposition->forwarding_note)
                            <div class="mt-3 bg-blue-50 rounded-lg p-3 border border-blue-200">
                                <p class="text-xs text-blue-600 font-semibold uppercase mb-1">Catatan Penerusan</p>
                                <p class="text-sm text-blue-700">{{ $disposition->forwarding_note }}</p>
                            </div>
                            @endif

                            <!-- Created Date -->
                            <div class="mt-3 flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Dibuat: {{ $disposition->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>

                        <!-- Right Side - Actions -->
                        <div class="ml-6 flex flex-col space-y-2">
                            <a href="{{ route('admin.disposisi.show', $disposition->id) }}" 
                               class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium text-center">
                                Lihat Detail
                            </a>
                            <button onclick="showQuickForwardModal({{ $disposition->id }}, '{{ $disposition->finalRecipient->name }}', '{{ $disposition->forwarding_note ?? '' }}')"
                               class="px-4 py-2 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 transition-all shadow-lg text-sm font-medium">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                Teruskan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($dispositions->hasPages())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            {{ $dispositions->links() }}
        </div>
        @endif
    @endif
</div>

<!-- Quick Forward Modal -->
<div id="quickForwardModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4 rounded-t-xl">
            <h3 class="text-xl font-bold text-white">Teruskan Disposisi</h3>
        </div>
        
        <form id="quickForwardForm" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <p class="text-sm text-blue-700">
                    Disposisi akan diteruskan ke:<br>
                    <strong class="text-blue-900" id="modalRecipientName"></strong>
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Penerusan (Opsional)</label>
                <textarea name="forwarding_note" 
                          id="modalForwardingNote"
                          rows="4" 
                          placeholder="Tambahkan catatan khusus untuk penerusan disposisi..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="hideQuickForwardModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 transition-all font-medium">
                    Teruskan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showQuickForwardModal(dispositionId, recipientName, forwardingNote) {
    const modal = document.getElementById('quickForwardModal');
    const form = document.getElementById('quickForwardForm');
    const recipientElement = document.getElementById('modalRecipientName');
    const noteElement = document.getElementById('modalForwardingNote');
    
    // Set form action
    form.action = `/admin/disposisi/${dispositionId}/forward`;
    
    // Set recipient name
    recipientElement.textContent = recipientName;
    
    // Set existing forwarding note if any
    noteElement.value = forwardingNote;
    
    // Show modal
    modal.classList.remove('hidden');
}

function hideQuickForwardModal() {
    document.getElementById('quickForwardModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('quickForwardModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideQuickForwardModal();
    }
});
</script>
@endsection