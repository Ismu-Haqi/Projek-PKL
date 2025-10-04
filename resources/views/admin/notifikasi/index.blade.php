@extends('admin.layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="p-6">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
            <p class="text-sm text-gray-500">Daftar pemberitahuan dan aktivitas sistem terbaru</p>
        </div>
        
        {{-- Tombol Mark All Read --}}
        <button type="button" 
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg shadow-md flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Tandai Semua Dibaca
        </button>
    </div>

    {{-- Daftar Notifikasi --}}
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold mb-4">Pemberitahuan Sistem</h3>
        
        <ul class="divide-y divide-gray-100">
            @forelse ($notifications ?? [] as $notification)
            <li class="p-4 flex justify-between items-center {{ ($notification->baca ?? false) ? 'bg-white' : 'bg-blue-50 border-l-4 border-blue-600' }}">
                <div class="flex items-center">
                    {{-- Ikon Status --}}
                    <span class="mr-4 text-xl {{ ($notification->baca ?? false) ? 'text-gray-400' : 'text-blue-600' }}">
                        @if ($notification->baca ?? false)
                            &#9989; {{-- Checkmark --}}
                        @else
                            &#128276; {{-- Bell --}}
                        @endif
                    </span>
                    
                    <div>
                        <p class="font-medium {{ ($notification->baca ?? false) ? 'text-gray-600' : 'text-gray-900' }}">
                            {{ $notification->pesan ?? 'Notifikasi baru.' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $notification->waktu ?? 'Baru saja' }}
                        </p>
                    </div>
                </div>
                
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Detail</a>
            </li>
            @empty
            <li class="p-4 text-center text-gray-500">Tidak ada notifikasi baru.</li>
            @endforelse
        </ul>
        
    </div>
</div>
@endsection