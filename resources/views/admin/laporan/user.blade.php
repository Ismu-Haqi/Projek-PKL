@extends('admin.layouts.app')

@section('title', 'Laporan Aktivitas User')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route('admin.laporan.index') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Laporan Aktivitas User</h1>
                <p class="text-gray-600 mt-1">Monitoring aktivitas dan statistik pengguna sistem</p>
            </div>
        </div>
        
        <div class="flex space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </button>
            <a href="{{ route('admin.laporan.export.pdf', ['type' => 'user']) }}" 
               class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
            <div class="text-sm text-blue-600 font-medium mb-1">Total User</div>
            <div class="text-3xl font-bold text-blue-700">{{ $users->total() }}</div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200 p-6">
            <div class="text-sm text-purple-600 font-medium mb-1">Administrator</div>
            <div class="text-3xl font-bold text-purple-700">{{ $users->where('role', 'admin')->count() }}</div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl border border-green-200 p-6">
            <div class="text-sm text-green-600 font-medium mb-1">Staff</div>
            <div class="text-3xl font-bold text-green-700">{{ $users->where('role', 'staff')->count() }}</div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl border border-orange-200 p-6">
            <div class="text-sm text-orange-600 font-medium mb-1">User Aktif</div>
            <div class="text-3xl font-bold text-orange-700">{{ $users->where('is_active', true)->count() }}</div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-50 to-teal-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Unit Kerja</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Arsip Dibuat</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Disposisi Dikirim</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Disposisi Diterima</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $users->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    Staff
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $user->unit ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 text-blue-700 font-bold">
                                {{ $user->archives_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-purple-100 text-purple-700 font-bold">
                                {{ $user->sent_dispositions_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-green-100 text-green-700 font-bold">
                                {{ $user->received_dispositions_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">Tidak ada data user</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Summary Stats -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Aktivitas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Arsip Dibuat</p>
                <p class="text-2xl font-bold text-blue-700">{{ $users->sum('archives_count') }}</p>
            </div>
            <div class="bg-white rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Disposisi Dikirim</p>
                <p class="text-2xl font-bold text-purple-700">{{ $users->sum('sent_dispositions_count') }}</p>
            </div>
            <div class="bg-white rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Disposisi Diterima</p>
                <p class="text-2xl font-bold text-green-700">{{ $users->sum('received_dispositions_count') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection