@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🔔 Notifikasi</h1>
        <p class="text-gray-600 mt-2">Kelola semua notifikasi Anda</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Notifikasi</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['total'] }}</h3>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Belum Dibaca</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['unread'] }}</h3>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Sudah Dibaca</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['read'] }}</h3>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Hari Ini</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['today'] }}</h3>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-2">
            <form action="{{ route('admin.notifikasi.read-all') }}" method="POST" class="inline" id="markAllReadForm">
                @csrf
                <button type="button" onclick="confirmMarkAllRead(this)" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tandai Semua Sudah Dibaca
                </button>
            </form>

            <form action="{{ route('admin.notifikasi.index') }}" method="GET" class="inline" id="clearReadForm">
                <button type="button" onclick="confirmClearRead(this)" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Yang Sudah Dibaca
                </button>
                <input type="hidden" name="clear_read" value="1">
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @forelse($notifications as $notification)
        <div class="flex items-start p-4 border-b hover:bg-gray-50 transition {{ $notification->isRead() ? 'bg-white' : 'bg-blue-50' }}">
            <!-- Icon -->
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-{{ $notification->icon_class['color'] }}-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-{{ $notification->icon_class['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $notification->icon_class['icon'] }}"/>
                </svg>
            </div>

            <!-- Content -->
            <div class="ml-4 flex-1">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-gray-800 {{ !$notification->isRead() ? 'font-bold' : '' }}">
                            {{ $notification->title }}
                            @if(!$notification->isRead())
                                <span class="inline-block w-2 h-2 bg-blue-600 rounded-full ml-2"></span>
                            @endif
                        </h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                        <p class="text-xs text-gray-400 mt-2">{{ $notification->time_ago }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 ml-4">
                        @if($notification->url)
                        <a href="{{ route('admin.notifikasi.read', $notification->id) }}" 
                           class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition" 
                           title="Lihat Detail">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        @endif

                        @if(!$notification->isRead())
                        <form action="{{ route('admin.notifikasi.read', $notification->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition" title="Tandai Sudah Dibaca">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('admin.notifikasi.destroy', $notification->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDeleteNotification(this, '{{ $notification->title }}')" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="mt-4 text-gray-500 font-medium">Tidak ada notifikasi</p>
            <p class="text-sm text-gray-400 mt-1">Notifikasi akan muncul di sini</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
    @endif

</div>

{{-- Include SweetAlert --}}
@include('partials.sweetalert')

{{-- Custom JavaScript untuk Notifikasi --}}
<script>
    // Fungsi konfirmasi hapus notifikasi individual
    window.confirmDeleteNotification = function(button, notificationTitle) {
        if (typeof Swal === 'undefined') {
            if (confirm('Hapus notifikasi ini?')) {
                button.closest('form').submit();
            }
            return;
        }

        Swal.fire({
            title: 'Hapus Notifikasi?',
            html: `
                <div class="text-left">
                    <p class="text-gray-700 mb-2">Notifikasi "<strong>${notificationTitle}</strong>" akan dihapus</p>
                    <p class="text-sm text-red-600">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup: 'animated-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Menghapus notifikasi...');
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.closest('form').submit();
            }
        });
    }

    // Fungsi konfirmasi tandai semua sudah dibaca
    window.confirmMarkAllRead = function(button) {
        if (typeof Swal === 'undefined') {
            if (confirm('Tandai semua notifikasi sudah dibaca?')) {
                button.closest('form').submit();
            }
            return;
        }

        Swal.fire({
            title: 'Tandai Semua Sudah Dibaca?',
            text: 'Semua notifikasi akan ditandai sebagai sudah dibaca',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-check-double mr-2"></i> Ya, Tandai Semua',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true,
            customClass: {
                popup: 'animated-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Memperbarui notifikasi...');
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
                button.closest('form').submit();
            }
        });
    }

    // Fungsi konfirmasi hapus notifikasi yang sudah dibaca
    window.confirmClearRead = function(button) {
        if (typeof Swal === 'undefined') {
            if (confirm('Hapus semua notifikasi yang sudah dibaca?')) {
                button.closest('form').submit();
            }
            return;
        }

        Swal.fire({
            title: 'Hapus Notifikasi yang Sudah Dibaca?',
            html: `
                <div class="text-left">
                    <p class="text-gray-700 mb-3">Semua notifikasi yang sudah dibaca akan dihapus</p>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                        </p>
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Ya, Hapus Semua!',
            cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup: 'animated-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Menghapus notifikasi...');
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...';
                button.closest('form').submit();
            }
        });
    }
</script>
@endsection