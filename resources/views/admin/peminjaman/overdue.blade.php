@extends('admin.layouts.app')

@section('title', 'Peminjaman Terlambat')

@section('content')
<div class="p-6">

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.peminjaman.index') }}" class="hover:text-blue-600">Manajemen Peminjaman</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Terlambat</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">⚠️ Peminjaman Terlambat</h1>
        <p class="text-sm text-gray-500 mt-1">Peminjaman yang melewati batas waktu pengembalian</p>
    </div>

    @if($borrows->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($borrows as $borrow)
        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition p-6 border-l-4 border-red-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-bold text-gray-800">{{ $borrow->asset->nama }}</h3>
                    <p class="text-xs text-gray-500 font-mono">{{ $borrow->kode_peminjaman }}</p>
                </div>
                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                    Terlambat {{ $borrow->keterlambatan }} Hari
                </span>
            </div>

            <div class="space-y-2 mb-4 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Peminjam:</span>
                    <span class="font-medium">{{ $borrow->borrower->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Unit:</span>
                    <span class="font-medium">{{ $borrow->borrower_unit }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Seharusnya Kembali:</span>
                    <span class="font-medium text-red-600">{{ $borrow->tanggal_kembali_rencana->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Durasi Pinjam:</span>
                    <span class="font-medium">{{ $borrow->lama_dipinjam }} Hari</span>
                </div>
            </div>

            <!-- Warning Box -->
            <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-4">
                <p class="text-xs text-red-800 font-semibold">
                    ⚠️ Segera hubungi peminjam untuk pengembalian aset!
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.peminjaman.show', $borrow->id) }}" 
                   class="flex-1 bg-red-600 text-white text-center px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm font-medium">
                    Tindak Lanjut
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if($borrows->hasPages())
    <div class="mt-6">
        {{ $borrows->links() }}
    </div>
    @endif

    @else
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="mx-auto h-24 w-24 text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Peminjaman Terlambat</h3>
        <p class="text-gray-500 mb-6">Semua peminjaman dikembalikan tepat waktu! 🎉</p>
        <a href="{{ route('admin.peminjaman.index') }}" 
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            Lihat Semua Peminjaman
        </a>
    </div>
    @endif

</div>
@endsection