@extends('staff.layouts.app')

@section('title', 'Manajemen Aset')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center mr-3 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                Manajemen Aset
            </h1>
            <p class="text-gray-600 mt-1 ml-15">Monitor dan kelola aset organisasi</p>
        </div>
        
        {{-- ✅ NEW: Tombol Tambah Aset (Hanya untuk Staff & Admin) --}}
        @if(Auth::user()->role === 'staff' || Auth::user()->role === 'admin')
        <a href="{{ route('staff.aset.create') }}"       
           class="flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg transition-all duration-200 text-base font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Aset</span>
        </a>
        @endif
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Total Aset</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Tersedia</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ $stats['tersedia'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Digunakan</p>
                    <h3 class="text-3xl font-bold text-blue-600">{{ $stats['digunakan'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Diperbaiki</p>
                    <h3 class="text-3xl font-bold text-yellow-600">{{ $stats['maintenance'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-xl bg-yellow-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Rusak</p>
                    <h3 class="text-3xl font-bold text-red-600">{{ $stats['rusak'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('staff.aset.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            {{-- Search --}}
            <div class="md:col-span-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama, kode, serial number..." 
                           class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Kategori --}}
            <div>
                <select name="kategori" class="w-full py-2.5 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div>
                <select name="status" class="w-full py-2.5 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="digunakan" {{ request('status') == 'digunakan' ? 'selected' : '' }}>Digunakan</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Diperbaiki</option>
                    <option value="rusak" {{ request('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>

            {{-- Unit --}}
            <div>
                <select name="unit" class="w-full py-2.5 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>
                            {{ $unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-gradient-to-r from-orange-600 to-red-600 text-white px-4 py-2.5 rounded-lg hover:from-orange-700 hover:to-red-700 transition-all flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('staff.aset.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </a>
            </div>
        </form>
    </div>

    {{-- Assets Grid --}}
    @if($assets->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($assets as $asset)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
            {{-- Asset Image --}}
            <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                @if($asset->foto)
                    <img src="{{ asset('storage/' . $asset->foto) }}" 
                         alt="{{ $asset->nama }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                @endif
                
                {{-- Status Badge --}}
                @php
                    $statusConfig = [
                        'tersedia' => ['bg' => 'bg-green-500', 'text' => 'Tersedia'],
                        'digunakan' => ['bg' => 'bg-blue-500', 'text' => 'Digunakan'],
                        'maintenance' => ['bg' => 'bg-yellow-500', 'text' => 'Diperbaiki'],
                        'rusak' => ['bg' => 'bg-red-500', 'text' => 'Rusak'],
                        'dipinjam' => ['bg' => 'bg-orange-500', 'text' => 'Dipinjam']
                    ];
                    $status = $statusConfig[$asset->status] ?? ['bg' => 'bg-gray-500', 'text' => ucfirst($asset->status)];
                @endphp
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 rounded-full text-white text-xs font-semibold shadow-lg {{ $status['bg'] }}">
                        {{ $status['text'] }}
                    </span>
                </div>
            </div>

            {{-- Asset Info --}}
            <div class="p-5">
                {{-- Name & Code --}}
                <h3 class="text-lg font-bold text-gray-800 mb-2 truncate" title="{{ $asset->nama }}">
                    {{ $asset->nama }}
                </h3>
                <p class="mb-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 font-mono">
                        {{ $asset->kode_asset }}
                    </span>
                </p>

                {{-- Details --}}
                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Kategori:</span>
                        <span class="font-medium text-gray-800">{{ $asset->kategori }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Merk:</span>
                        <span class="font-medium text-gray-800">{{ $asset->merk ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Lokasi:</span>
                        <span class="font-medium text-gray-800 truncate max-w-[150px]" title="{{ $asset->lokasi }}">
                            {{ $asset->lokasi ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Unit:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $asset->unit ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <span class="text-gray-600">Kondisi:</span>
                        @php
                            $kondisiConfig = [
                                'baik' => 'bg-green-100 text-green-800',
                                'cukup' => 'bg-blue-100 text-blue-800',
                                'kurang' => 'bg-yellow-100 text-yellow-800',
                                'rusak' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $kondisiConfig[$asset->kondisi] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($asset->kondisi) }}
                        </span>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="pt-3 border-t border-gray-100">
                    <a href="{{ route('staff.aset.show', $asset->id) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white rounded-lg transition-all duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Lihat Detail</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($assets->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">{{ $assets->firstItem() }}</span> sampai 
                <span class="font-medium">{{ $assets->lastItem() }}</span> dari 
                <span class="font-medium">{{ $assets->total() }}</span> aset
            </div>
            <div>
                {{ $assets->links() }}
            </div>
        </div>
    </div>
    @endif

    @else
    {{-- Empty State --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Aset</h3>
        <p class="text-gray-500 mb-6">Belum ada aset yang terdaftar dalam sistem</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Auto-submit form when filter changes
document.querySelectorAll('select[name="kategori"], select[name="status"], select[name="unit"]').forEach(select => {
    select.addEventListener('change', function() {
        this.form.submit();
    });
});
</script>
@endpush