<user class="blade php"></user>{{-- resources/views/dashboard/leader.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-800 leading-tight">
                    Dashboard <span class="text-indigo-600">Leader</span>
                </h2>
                @if(auth()->user()->division)
                    <p class="text-gray-600 mt-2">Manajemen Divisi {{ auth()->user()->division->name }}</p>
                @endif
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center space-x-2 bg-gradient-to-r from-indigo-50 to-purple-50 px-4 py-2 rounded-full">
                    <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                    <span class="text-sm font-medium text-gray-700">Status: Leader</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(auth()->user()->division)
                <!-- Header Divisi -->
                <div class="mb-8 bg-gradient-to-r from-indigo-600 to-purple-700 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 text-white">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div>
                                <h3 class="text-xl md:text-2xl font-bold mb-2">{{ auth()->user()->division->name }}</h3>
                                <p class="text-indigo-100">Dashboard Manajemen Divisi</p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <div class="flex items-center space-x-4">
                                    <div class="text-center">
                                        <div class="text-3xl font-bold">{{ $divisionMembers->count() }}</div>
                                        <div class="text-sm text-indigo-200">Total Anggota</div>
                                    </div>
                                    <div class="h-12 w-px bg-white/30"></div>
                                    <a href="{{ route('leader.verification.index') }}" 
                                       class="px-4 py-2 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-50 transition duration-300">
                                        Verifikasi Cuti
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Verifikasi Tertunda -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-orange-500 rounded-xl blur opacity-20 group-hover:opacity-30 transition duration-500"></div>
                        <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-red-50 rounded-lg">
                                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-semibold px-3 py-1 bg-red-100 text-red-700 rounded-full">Prioritas</span>
                                </div>
                                <p class="text-sm font-medium text-gray-600 mb-2">Verifikasi Tertunda</p>
                                <p class="text-3xl font-bold text-gray-900 mb-3">{{ $pendingVerifications }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">{{ $membersWithPending->count() ?? 0 }} anggota</span>
                                    <a href="{{ route('leader.verification.index') }}" 
                                       class="text-red-600 hover:text-red-800 font-medium text-sm">
                                        Tinjau →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pengajuan -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl blur opacity-20 group-hover:opacity-30 transition duration-500"></div>
                        <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-indigo-50 rounded-lg">
                                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-semibold px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full">Divisi</span>
                                </div>
                                <p class="text-sm font-medium text-gray-600 mb-2">Total Pengajuan</p>
                                <p class="text-3xl font-bold text-gray-900 mb-3">{{ $totalApplications }}</p>
                                <div class="text-xs text-gray-500">Semua status pengajuan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Sedang Cuti -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl blur opacity-20 group-hover:opacity-30 transition duration-500"></div>
                        <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-emerald-50 rounded-lg">
                                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-semibold px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full">Minggu Ini</span>
                                </div>
                                <p class="text-sm font-medium text-gray-600 mb-2">Sedang Cuti</p>
                                <p class="text-3xl font-bold text-gray-900 mb-3">{{ $onLeaveThisWeek->count() }}</p>
                                <div class="text-xs text-gray-500">Cuti aktif minggu ini</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dua Kolom Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Anggota dengan Pending -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Menunggu Verifikasi</h3>
                            <p class="text-sm text-gray-600">Anggota dengan pengajuan tertunda</p>
                        </div>
                        <div class="p-6">
                            @if($membersWithPending->count() > 0)
                                <div class="space-y-4">
                                    @foreach($membersWithPending as $member)
                                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-lg hover:bg-red-50 transition duration-300">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                                                    <img class="w-full h-full object-cover" 
                                                         src="{{ $member->profile_photo ? asset('storage/' . $member->profile_photo) : asset('images/default-avatar.png') }}" 
                                                         alt="{{ $member->name }}">
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $member->name }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $member->email }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-block px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                                    {{ $member->pending_leaves_count }} pending
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="inline-block p-6 bg-gray-50 rounded-lg">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-gray-600">Tidak ada pengajuan tertunda</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sedang Cuti Minggu Ini -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Sedang Cuti</h3>
                            <p class="text-sm text-gray-600">Anggota yang sedang cuti minggu ini</p>
                        </div>
                        <div class="p-6">
                            @if($onLeaveThisWeek->count() > 0)
                                <div class="space-y-4">
                                    @foreach($onLeaveThisWeek as $leave)
                                        <div class="flex items-center justify-between p-4 border border-emerald-100 bg-emerald-50 rounded-lg">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                                                    <img class="w-full h-full object-cover" 
                                                         src="{{ $leave->user->profile_photo ? asset('storage/' . $leave->user->profile_photo) : asset('images/default-avatar.png') }}" 
                                                         alt="{{ $leave->user->name }}">
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $leave->user->name }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $leave->type_label }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-700">
                                                    {{ $leave->start_date->format('d/M') }} - {{ $leave->end_date->format('d/M') }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $leave->total_days }} hari</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="inline-block p-6 bg-gray-50 rounded-lg">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <p class="text-gray-600">Tidak ada anggota yang cuti</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            @else
                <!-- Leader tanpa Divisi -->
                <div class="bg-gradient-to-r from-amber-400 to-orange-500 rounded-2xl shadow-xl overflow-hidden">
                    <div class="absolute inset-0 bg-black opacity-10"></div>
                    <div class="relative p-8 text-white text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6 backdrop-blur-sm">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.332 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Divisi Belum Ditugaskan</h3>
                        <p class="text-amber-100 mb-6 max-w-md mx-auto">
                            Anda saat ini ditetapkan sebagai Leader, namun belum ditugaskan untuk memimpin divisi. Silakan hubungi Administrator untuk penugasan divisi.
                        </p>
                        <a href="mailto:admin@company.com" 
                           class="inline-flex items-center px-6 py-3 bg-white text-amber-600 font-bold rounded-lg hover:bg-gray-50 transition duration-300 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Hubungi Admin
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>