@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-6xl">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.peminjaman.index') }}" class="hover:text-blue-600">Manajemen Peminjaman</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Detail Peminjaman</span>
        </div>
        
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $borrow->kode_peminjaman }}</h1>
                <p class="text-gray-600 mt-2">Detail peminjaman aset</p>
            </div>
            
            <!-- Status Badge -->
            @php
                $badge = $borrow->status_badge;
            @endphp
            <span class="px-4 py-2 rounded-full text-sm font-bold bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-800">
                {{ $badge['text'] }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Informasi Aset -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    📦 Informasi Aset
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Aset</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $borrow->asset->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kode Aset</p>
                        <p class="font-medium text-gray-800 mt-1 font-mono">{{ $borrow->asset->kode_asset }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kategori</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $borrow->asset->kategori }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Unit Pemilik</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $borrow->asset->unit ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('admin.aset.show', $borrow->asset_id) }}" 
                       class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Lihat Detail Aset
                    </a>
                </div>
            </div>

            <!-- Informasi Peminjam -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    👤 Informasi Peminjam
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Peminjam</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $borrow->borrower->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $borrow->borrower->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Unit Peminjam</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $borrow->borrower_unit }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Role</p>
                        <p class="font-medium text-gray-800 mt-1">{{ ucfirst($borrow->borrower->role) }}</p>
                    </div>
                </div>
            </div>

            <!-- Keperluan & Catatan -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    📝 Keperluan & Catatan
                </h3>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600 font-medium">Keperluan Peminjaman:</p>
                    <p class="text-gray-800 mt-2 bg-gray-50 p-3 rounded-lg">{{ $borrow->keperluan }}</p>
                </div>

                @if($borrow->catatan_peminjam)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 font-medium">Catatan Peminjam:</p>
                    <p class="text-gray-800 mt-2 bg-blue-50 p-3 rounded-lg border-l-4 border-blue-500">{{ $borrow->catatan_peminjam }}</p>
                </div>
                @endif

                @if($borrow->catatan_admin)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 font-medium">Catatan Admin:</p>
                    <p class="text-gray-800 mt-2 bg-yellow-50 p-3 rounded-lg border-l-4 border-yellow-500">{{ $borrow->catatan_admin }}</p>
                </div>
                @endif

                @if($borrow->catatan_pengembalian)
                <div>
                    <p class="text-sm text-gray-600 font-medium">Catatan Pengembalian:</p>
                    <p class="text-gray-800 mt-2 bg-green-50 p-3 rounded-lg border-l-4 border-green-500">{{ $borrow->catatan_pengembalian }}</p>
                </div>
                @endif
            </div>

            <!-- Kondisi Aset -->
            @if($borrow->kondisi_pinjam || $borrow->kondisi_kembali)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    🔧 Kondisi Aset
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    @if($borrow->kondisi_pinjam)
                    <div>
                        <p class="text-sm text-gray-600">Kondisi Saat Dipinjam</p>
                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($borrow->kondisi_pinjam) }}
                        </span>
                    </div>
                    @endif
                    
                    @if($borrow->kondisi_kembali)
                    <div>
                        <p class="text-sm text-gray-600">Kondisi Saat Dikembalikan</p>
                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-sm font-medium 
                            @if($borrow->kondisi_kembali === 'baik') bg-green-100 text-green-800
                            @elseif($borrow->kondisi_kembali === 'cukup') bg-blue-100 text-blue-800
                            @elseif($borrow->kondisi_kembali === 'kurang') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($borrow->kondisi_kembali) }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Foto Kondisi -->
                <div class="grid grid-cols-2 gap-4 mt-4">
                    @if($borrow->foto_pinjam)
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Foto Saat Dipinjam:</p>
                        <img src="{{ asset('storage/' . $borrow->foto_pinjam) }}" alt="Foto Pinjam" class="rounded-lg shadow-md w-full">
                    </div>
                    @endif
                    
                    @if($borrow->foto_kembali)
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Foto Saat Dikembalikan:</p>
                        <img src="{{ asset('storage/' . $borrow->foto_kembali) }}" alt="Foto Kembali" class="rounded-lg shadow-md w-full">
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>

        <!-- Right Column - Timeline & Actions -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ⏱️ Timeline
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                            <p class="font-medium text-gray-800">{{ $borrow->tanggal_pengajuan->format('d M Y') }}</p>
                        </div>
                    </div>

                    @if($borrow->tanggal_pinjam)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Pengambilan</p>
                            <p class="font-medium text-gray-800">{{ $borrow->tanggal_pinjam->format('d M Y') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3"></div>
                        <div>
                            <p class="text-sm text-gray-600">Batas Pengembalian</p>
                            <p class="font-medium text-gray-800">{{ $borrow->tanggal_kembali_rencana->format('d M Y') }}</p>
                            @if($borrow->isOverdue())
                                <span class="text-xs text-red-600 font-semibold">Terlambat {{ $borrow->keterlambatan }} hari!</span>
                            @endif
                        </div>
                    </div>

                    @if($borrow->tanggal_kembali_aktual)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3"></div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Pengembalian</p>
                            <p class="font-medium text-gray-800">{{ $borrow->tanggal_kembali_aktual->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($borrow->approved_by)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-indigo-500 rounded-full mt-2 mr-3"></div>
                        <div>
                            <p class="text-sm text-gray-600">Disetujui Oleh</p>
                            <p class="font-medium text-gray-800">{{ $borrow->approver->name ?? '-' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">⚡ Aksi</h3>
                
                @if($borrow->status === 'pending')
                <!-- Form Setujui -->
                <form action="{{ route('admin.peminjaman.approve', $borrow->id) }}" method="POST" class="mb-3">
                    @csrf
                    <input type="date" name="tanggal_pinjam" required 
                           class="w-full mb-2 px-3 py-2 border rounded-lg text-sm" 
                           min="{{ date('Y-m-d') }}"
                           value="{{ date('Y-m-d') }}">
                    <textarea name="catatan_admin" rows="2" 
                              class="w-full mb-2 px-3 py-2 border rounded-lg text-sm" 
                              placeholder="Catatan (opsional)"></textarea>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        ✅ Setujui Peminjaman
                    </button>
                </form>

                <!-- Form Tolak -->
                <button onclick="showRejectModal()" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    ❌ Tolak Peminjaman
                </button>
                @endif

                @if($borrow->status === 'approved')
                <!-- Form Serahkan Aset -->
                <button onclick="showHandoverModal()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    🚚 Serahkan Aset
                </button>
                @endif

                @if(in_array($borrow->status, ['borrowed', 'overdue']))
                <!-- Form Terima Pengembalian -->
                <button onclick="showReturnModal()" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                    📥 Terima Pengembalian
                </button>
                @endif

                @if(in_array($borrow->status, ['pending', 'rejected']))
                <!-- Form Hapus -->
                <form action="{{ route('admin.peminjaman.destroy', $borrow->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete(this, 'Hapus peminjaman {{ $borrow->kode_peminjaman }}?')" 
                            class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        🗑️ Hapus
                    </button>
                </form>
                @endif
            </div>

            <!-- Back Button -->
            <a href="{{ route('admin.peminjaman.index') }}" 
               class="block text-center bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                ← Kembali
            </a>
        </div>
    </div>

</div>

<!-- Modal Tolak -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Tolak Peminjaman</h3>
        <form action="{{ route('admin.peminjaman.reject', $borrow->id) }}" method="POST">
            @csrf
            <textarea name="catatan_admin" required rows="4" 
                      class="w-full px-3 py-2 border rounded-lg mb-4" 
                      placeholder="Alasan penolakan (wajib)"></textarea>
            <div class="flex gap-2">
                <button type="button" onclick="hideRejectModal()" 
                        class="flex-1 bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</button>
                <button type="submit" 
                        class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Tolak</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Serahkan -->
<div id="handoverModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Serahkan Aset</h3>
        <form action="{{ route('admin.peminjaman.handover', $borrow->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <select name="kondisi_pinjam" required class="w-full px-3 py-2 border rounded-lg mb-3">
                <option value="">Pilih Kondisi Aset</option>
                <option value="baik">Baik</option>
                <option value="cukup">Cukup</option>
                <option value="kurang">Kurang</option>
                <option value="rusak">Rusak</option>
            </select>
            <input type="file" name="foto_pinjam" accept="image/*" class="w-full mb-3 text-sm">
            <textarea name="catatan_admin" rows="3" 
                      class="w-full px-3 py-2 border rounded-lg mb-4" 
                      placeholder="Catatan (opsional)"></textarea>
            <div class="flex gap-2">
                <button type="button" onclick="hideHandoverModal()" 
                        class="flex-1 bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</button>
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Serahkan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Terima Pengembalian -->
<div id="returnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Terima Pengembalian</h3>
        <form action="{{ route('admin.peminjaman.return', $borrow->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <select name="kondisi_kembali" required class="w-full px-3 py-2 border rounded-lg mb-3">
                <option value="">Pilih Kondisi Aset</option>
                <option value="baik">Baik</option>
                <option value="cukup">Cukup</option>
                <option value="kurang">Kurang</option>
                <option value="rusak">Rusak</option>
            </select>
            <input type="file" name="foto_kembali" accept="image/*" class="w-full mb-3 text-sm">
            <textarea name="catatan_pengembalian" rows="3" 
                      class="w-full px-3 py-2 border rounded-lg mb-4" 
                      placeholder="Catatan pengembalian (opsional)"></textarea>
            <div class="flex gap-2">
                <button type="button" onclick="hideReturnModal()" 
                        class="flex-1 bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</button>
                <button type="submit" 
                        class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Terima</button>
            </div>
        </form>
    </div>
</div>

@include('partials.sweetalert')

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}
function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
function showHandoverModal() {
    document.getElementById('handoverModal').classList.remove('hidden');
}
function hideHandoverModal() {
    document.getElementById('handoverModal').classList.add('hidden');
}
function showReturnModal() {
    document.getElementById('returnModal').classList.remove('hidden');
}
function hideReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
}
</script>

@endsection