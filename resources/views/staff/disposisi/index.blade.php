@extends('staff.layouts.app')

@section('title', 'Disposisi Saya')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center mr-3 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                Disposisi Saya
            </h1>
            <p class="text-gray-600 mt-1 ml-15">Kelola disposisi surat yang diterima</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Total Disposisi</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Menunggu</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-yellow-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Diproses</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Selesai</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('staff.disposisi.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nomor disposisi, subjek..." 
                        class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Priority Filter -->
            <div>
                <select name="priority" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Prioritas</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Sangat Mendesak</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Mendesak</option>
                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Biasa</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Tidak Mendesak</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Dispositions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. Disposisi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Subjek & Arsip</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pengirim</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Prioritas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Deadline</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($dispositions as $disposition)
                    <tr class="hover:bg-gray-50 transition-colors {{ !$disposition->isRead() ? 'bg-blue-50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if(!$disposition->isRead())
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse" title="Belum dibaca"></div>
                                @endif
                                <span class="font-semibold text-gray-800">{{ $disposition->nomor_disposisi }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $disposition->created_at->format('d M Y, H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ Str::limit($disposition->subject, 40) }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $disposition->archive->nomor_surat ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold text-xs mr-2">
                                    {{ strtoupper(substr($disposition->fromUser->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">{{ $disposition->fromUser->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $disposition->fromUser->unit ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $priorityConfig = [
                                    'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => true],
                                    'high' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'icon' => false],
                                    'normal' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => false],
                                    'low' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'icon' => false]
                                ];
                                $config = $priorityConfig[$disposition->priority];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                                @if($config['icon'])
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                @endif
                                {{ $disposition->priorityLabel['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200'],
                                    'in_progress' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                                    'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200'],
                                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200']
                                ];
                                $statusStyle = $statusConfig[$disposition->status];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusStyle['bg'] }} {{ $statusStyle['text'] }} {{ $statusStyle['border'] }}">
                                {{ $disposition->statusLabel['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($disposition->deadline)
                                <div class="text-sm">
                                    <p class="font-medium text-gray-800">{{ $disposition->deadline->format('d M Y') }}</p>
                                    @if($disposition->isOverdue())
                                        <p class="text-xs text-red-600 font-semibold mt-1 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                            </svg>
                                            Terlambat!
                                        </p>
                                    @elseif($disposition->days_until_deadline !== null && $disposition->days_until_deadline >= 0)
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $disposition->days_until_deadline }} hari lagi
                                        </p>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('staff.disposisi.show', $disposition->id) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                   title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                @if($disposition->status !== 'completed' && $disposition->status !== 'rejected')
                                <button onclick="quickUpdateStatus({{ $disposition->id }}, '{{ $disposition->status }}')" 
                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                        title="Update Status">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">Tidak ada disposisi</p>
                                <p class="text-gray-400 text-sm mt-1">Belum ada disposisi yang diterima saat ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($dispositions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $dispositions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Quick Update Status Modal -->
<div id="quickStatusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Update Status Disposisi</h3>
        </div>
        <form id="quickStatusForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Baru</label>
                <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="in_progress">Sedang Diproses</option>
                    <option value="completed">Selesai</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="notes" rows="3" placeholder="Berikan catatan terkait update status..." 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>

            <div class="flex space-x-3">
                <button type="button" onclick="closeQuickStatusModal()" 
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </button>
                <button type="submit" 
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all font-medium">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Auto-submit form when filter changes
document.querySelectorAll('select[name="status"], select[name="priority"]').forEach(select => {
    select.addEventListener('change', function() {
        this.form.submit();
    });
});

// Quick Update Status Modal
function quickUpdateStatus(dispositionId, currentStatus) {
    const modal = document.getElementById('quickStatusModal');
    const form = document.getElementById('quickStatusForm');
    
    form.action = `/staff/disposisi/${dispositionId}/status`;
    
    // Set initial status
    const statusSelect = form.querySelector('select[name="status"]');
    if (currentStatus === 'pending') {
        statusSelect.value = 'in_progress';
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeQuickStatusModal() {
    const modal = document.getElementById('quickStatusModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('quickStatusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQuickStatusModal();
    }
});
</script>
@endpush