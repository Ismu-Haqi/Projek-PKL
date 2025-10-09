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

        @if(Auth::user()->role === 'admin')
        <div class="flex space-x-2">
            <a href="{{ route('admin.disposisi.edit', $disposition->id) }}" 
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
                            <a href="{{ route(Auth::user()->role . '.arsip.show', $disposition->archive_id) }}" 
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
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Catatan Penerima</label>
                        <div class="mt-2 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $disposition->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Update Status Form (Staff Only) -->
            @if(Auth::user()->role === 'staff' && $disposition->status !== 'completed' && $disposition->status !== 'rejected')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Update Status Disposisi</h2>
                </div>
                
                <form action="{{ route('staff.disposisi.updateStatus', $disposition->id) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="in_progress" {{ $disposition->status == 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="completed">Selesai</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan/Feedback</label>
                        <textarea name="notes" rows="4" 
                            placeholder="Berikan catatan atau feedback terkait disposisi ini..."
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

                        <!-- Read -->
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

                        <!-- In Progress -->
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

                        <!-- Completed -->
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
                            </div>
                        </div>
                        @endif

                        <!-- Rejected -->
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
                    <!-- Status Badge -->
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

                    <!-- Priority Badge -->
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

                    <!-- Deadline -->
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
                    <!-- From -->
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

                    <!-- To -->
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
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route(Auth::user()->role . '.arsip.show', $disposition->archive_id) }}" 
                       class="block w-full px-4 py-2 bg-white text-center rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700 border border-gray-200">
                        📄 Lihat Arsip Terkait
                    </a>
                    <a href="{{ route(Auth::user()->role . '.arsip.download', $disposition->archive_id) }}" 
                       class="block w-full px-4 py-2 bg-white text-center rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700 border border-gray-200">
                        ⬇️ Download Arsip
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection