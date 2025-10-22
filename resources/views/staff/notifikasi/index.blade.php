@extends('staff.layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-xl animate-fade-in shadow-lg">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- Header Section with Gradient --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-500 via-purple-600 to-pink-600 rounded-2xl shadow-2xl mb-8 p-8">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/20 backdrop-blur-lg p-4 rounded-2xl">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h1 class="text-4xl font-bold mb-1">🔔 Notifikasi</h1>
                        <p class="text-white/90 text-lg">Kelola semua pemberitahuan Anda</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 backdrop-blur-lg rounded-xl p-4 text-white text-center border border-white/30">
                        <p class="text-3xl font-bold">{{ $stats['unread'] }}</p>
                        <p class="text-sm text-white/80">Belum Dibaca</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Total Notifikasi</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-4 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Belum Dibaca</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $stats['unread'] }}</h3>
                </div>
                <div class="bg-gradient-to-br from-red-100 to-red-200 p-4 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Sudah Dibaca</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $stats['read'] }}</h3>
                </div>
                <div class="bg-gradient-to-br from-green-100 to-green-200 p-4 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Hari Ini</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $stats['today'] }}</h3>
                </div>
                <div class="bg-gradient-to-br from-purple-100 to-purple-200 p-4 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
        <div class="flex flex-wrap gap-3">
            @if($stats['unread'] > 0)
            <form action="{{ route('staff.notifikasi.read-all') }}" method="POST" class="inline-flex">
                @csrf
                <button type="submit" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tandai Semua Sudah Dibaca
                </button>
            </form>
            @endif

            @if($stats['read'] > 0)
            <form action="{{ route('staff.notifikasi.index') }}" method="GET" class="inline-flex" onsubmit="return confirm('Hapus semua notifikasi yang sudah dibaca?')">
                <button type="submit" name="clear_read" value="1" class="inline-flex items-center bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Yang Sudah Dibaca
                </button>
            </form>
            @endif

            <div class="flex-1"></div>

            <div class="text-sm text-gray-500 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Total {{ $notifications->total() }} notifikasi</span>
            </div>
        </div>
    </div>

    {{-- Notifications List --}}
    @if($notifications->count() > 0)
    <div class="space-y-4">
        @foreach($notifications as $notification)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-2xl {{ $notification->isRead() ? '' : 'ring-2 ring-blue-400' }}">
            <div class="p-6">
                <div class="flex items-start">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-{{ $notification->icon_class['color'] }}-100 flex items-center justify-center mr-4 shadow-md">
                        <svg class="w-7 h-7 text-{{ $notification->icon_class['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $notification->icon_class['icon'] }}"/>
                        </svg>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <h4 class="text-base font-bold text-gray-800">
                                    {{ $notification->title }}
                                </h4>
                                @if(!$notification->isRead())
                                <span class="flex items-center">
                                    <span class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                    </span>
                                </span>
                                @endif
                            </div>
                            
                            {{-- Status Badge --}}
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $notification->isRead() ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-700' }}">
                                {{ $notification->isRead() ? 'Sudah Dibaca' : 'Baru' }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-3 leading-relaxed">{{ $notification->message }}</p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $notification->time_ago }}
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2">
                                @if($notification->url)
                                <a href="{{ route('staff.notifikasi.read', $notification->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat Detail
                                </a>
                                @endif

                                @if(!$notification->isRead())
                                <form action="{{ route('staff.notifikasi.read.post', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Tandai Sudah Dibaca">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('staff.notifikasi.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mt-8">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Menampilkan <span class="font-semibold text-gray-800">{{ $notifications->firstItem() }}</span> - 
                <span class="font-semibold text-gray-800">{{ $notifications->lastItem() }}</span> dari 
                <span class="font-semibold text-gray-800">{{ $notifications->total() }}</span> notifikasi
            </div>
            <div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
    @endif

    @else
    {{-- Empty State --}}
    <div class="bg-white rounded-2xl shadow-xl p-16 text-center border border-gray-100">
        <div class="max-w-md mx-auto">
            <div class="relative inline-block mb-6">
                <div class="absolute inset-0 bg-blue-200 rounded-full blur-2xl opacity-50 animate-pulse"></div>
                <div class="relative bg-gradient-to-br from-blue-100 to-purple-100 p-8 rounded-full">
                    <svg class="w-20 h-20 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-800 mb-3">Tidak Ada Notifikasi</h3>
            <p class="text-gray-500 text-lg mb-8">Anda belum memiliki notifikasi saat ini. Notifikasi akan muncul di sini ketika ada update baru.</p>
            
            <a href="{{ route('staff.dashboard') }}" 
               class="inline-flex items-center bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>
    @endif

</div>

@push('styles')
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-hide success messages
setTimeout(() => {
    document.querySelectorAll('.animate-fade-in').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);

// Auto-refresh unread count every 30 seconds
setInterval(() => {
    fetch('{{ route("staff.notifikasi.unread-count") }}')
        .then(response => response.json())
        .then(data => {
            // Update badge if needed
            console.log('Unread count:', data.count);
        })
        .catch(error => console.error('Error:', error));
}, 30000);
</script>
@endpush

@endsection