@extends('staff.layouts.app') 

@section('title', 'Arsip Digital')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Success/Error Messages --}}
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

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-xl animate-fade-in shadow-lg">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- Header Section --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-500 via-blue-600 to-purple-600 rounded-2xl shadow-2xl mb-8 p-8">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">📁 Arsip Digital</h1>
                    <p class="text-blue-100 text-lg">Akses dan kelola dokumen arsip digital</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 backdrop-blur-lg rounded-xl p-4 text-white text-center border border-white/30">
                        <p class="text-3xl font-bold">{{ $totalArchives ?? 0 }}</p>
                        <p class="text-sm text-white/80">Total Arsip</p>
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
                    <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Total Arsip</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $totalArchives ?? 0 }}</h3>
                </div>
                <div class="bg-gradient-to-br from-blue-100 to-blue-200 p-4 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Favorit</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $favoritesCount ?? 0 }}</h3>
                </div>
                <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 p-4 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.929 8.72c-.783-.57-.381-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Kategori</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $categoriesCount ?? 0 }}</h3>
                </div>
                <div class="bg-gradient-to-br from-green-100 to-green-200 p-4 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="group bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Bulan Ini</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $thisMonthCount ?? 0 }}</h3>
                </div>
                <div class="bg-gradient-to-br from-purple-100 to-purple-200 p-4 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <form action="{{ route('staff.arsip.index') }}" method="GET" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            {{-- Search --}}
            <div class="md:col-span-5">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nomor surat, judul, atau pengirim..." 
                           class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                </div>
            </div>

            {{-- Category --}}
            <div class="md:col-span-3">
                <select name="category" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Unit --}}
            <div class="md:col-span-2">
                <select name="unit" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                    <option value="">Semua Unit</option>
                    @foreach($units ?? [] as $unit)
                        <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>
                            {{ $unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Actions --}}
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                    Cari
                </button>
                <a href="{{ route('staff.arsip.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </a>
            </div>
        </div>
    </form>

    {{-- Archives Grid --}}
    @if($archives->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($archives as $archive)
        <div class="group bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-2">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <span class="inline-flex items-center bg-white/25 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-bold mb-2">
                            {{ $archive->nomor_surat }}
                        </span>
                        @if($archive->category)
                        <div class="mt-2">
                            <span class="inline-flex items-center bg-blue-900/50 backdrop-blur-sm text-white px-3 py-1 rounded-lg text-xs font-semibold">
                                {{ $archive->category->name }}
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Favorite Star --}}
                    <form action="{{ route('staff.arsip.favorite', $archive->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-white/25 backdrop-blur-sm hover:bg-white/40 p-2.5 rounded-xl transition-all transform hover:scale-110">
                            <svg class="w-6 h-6 text-white {{ $archive->is_favorite ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="p-6">
                {{-- Title --}}
                <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors cursor-pointer" 
                    onclick="window.location='{{ route('staff.arsip.show', $archive->id) }}'">
                    {{ $archive->judul }}
                </h3>

                {{-- Metadata --}}
                <div class="space-y-2 mb-5">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($archive->tanggal_arsip)->format('d M Y') }}</span>
                    </div>
                    
                    @if($archive->pengirim)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="truncate">{{ $archive->pengirim }}</span>
                    </div>
                    @endif
                    
                    @if($archive->unit)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="bg-purple-100 text-purple-700 px-2.5 py-1 rounded-lg text-xs font-semibold">
                            {{ $archive->unit }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('staff.arsip.show', $archive->id) }}" 
                       class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Detail
                    </a>
                    <a href="{{ route('staff.arsip.download', $archive->id) }}" 
                       class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($archives->hasPages())
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Menampilkan <span class="font-semibold text-gray-800">{{ $archives->firstItem() }}</span> - 
                <span class="font-semibold text-gray-800">{{ $archives->lastItem() }}</span> dari 
                <span class="font-semibold text-gray-800">{{ $archives->total() }}</span> arsip
            </div>
            <div>
                {{ $archives->links() }}
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Arsip</h3>
            <p class="text-gray-500 text-lg mb-8">Tidak ada dokumen arsip yang tersedia saat ini</p>
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
// Auto-hide messages
setTimeout(() => {
    document.querySelectorAll('.animate-fade-in').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);
</script>
@endpush

@endsection