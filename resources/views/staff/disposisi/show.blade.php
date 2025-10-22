@extends('staff.layouts.app')

@section('title', 'Detail Disposisi')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('staff.disposisi.index') }}" 
               class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Disposisi</h1>
                <p class="text-gray-600 mt-1">{{ $disposition->nomor_disposisi }}</p>
            </div>
        </div>

        @if(!$disposition->isRead())
        <div class="px-4 py-2 bg-yellow-100 border border-yellow-300 text-yellow-700 rounded-lg text-sm font-medium">
            📬 Disposisi Baru
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
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

                    <!-- Archive Reference -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Referensi Surat</label>
                        <div class="flex items-center justify-between mt-2">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $disposition->archive->nomor_surat }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $disposition->archive->judul }}</p>
                            </div>
                            <a href="{{ route('staff.arsip.show', $disposition->archive_id) }}" 
                               class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                Lihat Arsip
                            </a>
                        </div>
                    </div>

                    <!-- Instruction -->
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Instruksi</label>
                        <div class="mt-2 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $disposition->instruction }}</p>
                        </div>
                    </div>

                    <!-- Notes (if exists) -->
                    @if($disposition->notes)
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Catatan Anda</label>
                        <div class="mt-2 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $disposition->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Update Status Form (if not completed/rejected) -->
            @if($disposition->status !== 'completed' && $disposition->status !== 'rejected')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Update Status Disposisi
                    </h2>
                </div>
                
                <form action="{{ route('staff.disposisi.updateStatus', $disposition->id) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                            <option value="">-- Pilih Status --</option>
                            @if($disposition->status === 'pending')
                            <option value="in_progress" selected>🔄 Sedang Diproses</option>
                            @else
                            <option value="in_progress" {{ $disposition->status == 'in_progress' ? 'selected' : '' }}>🔄 Sedang Diproses</option>
                            @endif
                            <option value="completed">✅ Selesai</option>
                            <option value="rejected">❌ Ditolak</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-2">Pilih status sesuai dengan progress penyelesaian disposisi</p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan / Feedback</label>
                        <textarea name="notes" rows="4" 
                            placeholder="Berikan catatan atau feedback terkait disposisi ini (opsional)..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">{{ $disposition->notes }}</textarea>
                        <p class="text-xs text-gray-500 mt-2">Catatan ini akan dilihat oleh pengirim disposisi</p>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Tips Update Status:</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-700">
                                    <li><strong>Sedang Diproses:</strong> Jika Anda masih mengerjakan disposisi ini</li>
                                    <li><strong>Selesai:</strong> Jika disposisi sudah selesai dikerjakan</li>
                                    <li><strong>Ditolak:</strong> Jika disposisi tidak dapat diselesaikan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex space-x-3 pt-2">
                        <button type="button" onclick="window.history.back()" 
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                            Batal
                        </button>
                        <button type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 transition-all shadow-lg font-medium flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Status
                        </button>
                    </div>
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
                    <div class="relative space-y-6">
                        <!-- Vertical Line -->
                        <div class="absolute left-5 top-6 bottom-6 w-0.5 bg-gray-200"></div>

                        <!-- Created -->
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4 relative z-10">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                </svg>
                            </div>
                            <div class="flex-1 bg-gray-50 rounded-lg p-4">
                                <p class="font-semibold text-gray-800">Disposisi Dibuat</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $disposition->created_at->format('d M Y, H:i') }} WIB</p>
                                <p class="text-sm text-gray-500 mt-1">Oleh: {{ $disposition->fromUser->name }}</p>
                            </div>
                        </div>

                        <!-- Read -->
                        @if($disposition->read_at)
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-4 relative z-10">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 bg-gray-50 rounded-lg p-4">
                                <p class="font-semibold text-gray-800">Disposisi Dibaca</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $disposition->read_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        @endif

                        <!-- In Progress -->
                        @if($disposition->status === 'in_progress' || $disposition->status === 'completed')
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4 relative z-10">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
                                </svg>
                            </div>
                            <div class="flex-1 bg-gray-50 rounded-lg p-4">
                                <p class="font-semibold text-gray-800">Sedang Diproses</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $disposition->updated_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        @endif

                        <!-- Completed -->
                        @if($disposition->completed_at)
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-4 relative z-10">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                            </div>
                            <div class="flex-1 bg-green-50 rounded-lg p-4 border border-green-200">
                                <p class="font-semibold text-green-800">✅ Disposisi Selesai</p>
                                <p class="text-sm text-green-600 mt-1">{{ $disposition->completed_at->format('d M Y, H:i') }} WIB</p>
                                @if($disposition->notes)
                                <div class="mt-3 p-3 bg-white rounded border border-green-200">
                                    <p class="text-xs font-semibold text-gray-600 mb-1">Catatan:</p>
                                    <p class="text-sm text-gray-700">{{ $disposition->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Rejected -->
                        @if($disposition->status === 'rejected')
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-4 relative z-10">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                </svg>
                            </div>
                            <div class="flex-1 bg-red-50 rounded-lg p-4 border border-red-200">
                                <p class="font-semibold text-red-800">❌ Disposisi Ditolak</p>
                                <p class="text-sm text-red-600 mt-1">{{ $disposition->updated_at->format('d M Y, H:i') }} WIB</p>
                                @if($disposition->notes)
                                <div class="mt-3 p-3 bg-white rounded border border-red-200">
                                    <p class="text-xs font-semibold text-gray-600 mb-1">Alasan:</p>
                                    <p class="text-sm text-gray-700">{{ $disposition->notes }}</p>
                                </div>
                                @endif
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
                    <!-- Status Badge -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Status</label>
                        @php
                            $statusConfig = [
                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200'],
                                'in_progress' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                                'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200'],
                                'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200']
                            ];
                            $statusStyle = $statusConfig[$disposition->status];
                        @endphp
                        <div class="mt-2">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold border {{ $statusStyle['bg'] }} {{ $statusStyle['text'] }} {{ $statusStyle['border'] }}">
                                {{ $disposition->statusLabel['text'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Priority Badge -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Prioritas</label>
                        @php
                            $priorityConfig = [
                                'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                                'high' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'border-orange-200'],
                                'normal' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                                'low' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200']
                            ];
                            $priorityStyle = $priorityConfig[$disposition->priority];
                        @endphp
                        <div class="mt-2">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold border {{ $priorityStyle['bg'] }} {{ $priorityStyle['text'] }} {{ $priorityStyle['border'] }}">
                                @if($disposition->priority === 'urgent')
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                @endif
                                {{ $disposition->priorityLabel['text'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Deadline -->
                    @if($disposition->deadline)
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Batas Waktu</label>
                        <div class="mt-2 p-3 {{ $disposition->isOverdue() ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg">
                            <p class="font-bold text-gray-800">{{ $disposition->deadline->format('d M Y') }}</p>
                            @if($disposition->isOverdue())
                                <p class="text-xs text-red-600 font-semibold mt-1 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    ⚠️ Terlambat!
                                </p>
                            @elseif($disposition->days_until_deadline !== null && $disposition->days_until_deadline >= 0)
                                <p class="text-xs text-gray-600 mt-1">⏰ {{ $disposition->days_until_deadline }} hari lagi</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- People Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800">Informasi Pengirim</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold text-lg mr-3">
                            {{ strtoupper(substr($disposition->fromUser->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $disposition->fromUser->name }}</p>
                            <p class="text-sm text-gray-500">{{ $disposition->fromUser->unit ?? 'Staff' }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $disposition->fromUser->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('staff.arsip.show', $disposition->archive_id) }}" 
                       class="block w-full px-4 py-3 bg-white text-center rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700 border border-gray-200">
                        📄 Lihat Arsip Terkait
                    </a>
                    <a href="{{ route('staff.arsip.download', $disposition->archive_id) }}" 
                       class="block w-full px-4 py-3 bg-white text-center rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700 border border-gray-200">
                        ⬇️ Download Arsip
                    </a>
                    <button onclick="window.print()" 
                       class="block w-full px-4 py-3 bg-white text-center rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700 border border-gray-200">
                        🖨️ Cetak Disposisi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white;
    }
    
    .rounded-xl {
        border-radius: 0 !important;
    }
}
</style>
@endpush