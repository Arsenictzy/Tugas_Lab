{{-- resources/views/dashboard/user.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-800 leading-tight">
                    Dashboard <span class="text-blue-600">Karyawan</span>
                </h2>
                <p class="text-gray-600 mt-2">Selamat datang di sistem manajemen cuti perusahaan</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center space-x-2 bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-2 rounded-full">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-gray-700">Status: Aktif</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards dengan Animasi -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Sisa Cuti Card -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl blur opacity-20 group-hover:opacity-30 transition duration-500"></div>
                    <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold px-3 py-1 bg-blue-100 text-blue-700 rounded-full">Cuti Tahunan</span>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-2">Sisa Cuti Tahunan</p>
                            <p class="text-3xl font-bold text-gray-900 mb-3">{{ $remainingQuota }} <span class="text-lg font-normal text-gray-500">hari</span></p>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full" 
                                     style="width: {{ min(($remainingQuota/12)*100, 100) }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Reset setiap tahun kalender</p>
                        </div>
                    </div>
                </div>

                <!-- Cuti Sakit Card -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl blur opacity-20 group-hover:opacity-30 transition duration-500"></div>
                    <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-emerald-50 rounded-lg">
                                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full">Disetujui</span>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-2">Total Cuti Sakit</p>
                            <p class="text-3xl font-bold text-gray-900 mb-3">{{ $sickLeaves }} <span class="text-lg font-normal text-gray-500">permintaan</span></p>
                            <div class="flex items-center text-sm text-emerald-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Semua telah disetujui</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Permohonan Card -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl blur opacity-20 group-hover:opacity-30 transition duration-500"></div>
                    <div class="relative bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-purple-50 rounded-lg">
                                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold px-3 py-1 bg-purple-100 text-purple-700 rounded-full">Semua Status</span>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-2">Total Permohonan</p>
                            <p class="text-3xl font-bold text-gray-900 mb-3">{{ $totalApplications }} <span class="text-lg font-normal text-gray-500">pengajuan</span></p>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span>Semua jenis pengajuan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dua Kolom Konten -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Kiri - Profil dan Aksi Cepat -->
                <div class="space-y-6">
                    <!-- Profil Card -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Profil Saya</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="w-16 h-16 rounded-full overflow-hidden border-4 border-white shadow-md">
                                    <img class="w-full h-full object-cover" 
                                         src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/default-avatar.png') }}" 
                                         alt="{{ Auth::user()->name }}">
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-gray-900">{{ Auth::user()->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 w-8">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Peran</p>
                                        <p class="font-medium text-gray-900 capitalize">{{ Auth::user()->role }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 w-8">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Divisi</p>
                                        <p class="font-medium text-gray-900">{{ $division->name ?? 'Belum Ditugaskan' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 w-8">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Bergabung</p>
                                        <p class="font-medium text-gray-900">{{ Auth::user()->join_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('profile.edit') }}" 
                               class="mt-6 w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Profil
                            </a>
                        </div>
                    </div>

                    <!-- Quick Action Card -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 text-white">
                            <div class="flex items-center mb-4">
                                <div class="p-3 bg-white/20 rounded-lg mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">Ajukan Cuti Baru</h3>
                                    <p class="text-blue-100 text-sm">Proses cepat dan mudah</p>
                                </div>
                            </div>
                            <a href="{{ route('leave-applications.create') }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-50 transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Buat Pengajuan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan - Riwayat Cuti -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Riwayat Cuti Terbaru</h3>
                                    <p class="text-sm text-gray-600">5 pengajuan terakhir Anda</p>
                                </div>
                                <a href="{{ route('leave-applications.index') }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            @if($recentLeaves->count() > 0)
                                <div class="space-y-4">
                                    @foreach ($recentLeaves as $leave)
                                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition duration-300">
                                            <div class="flex items-center">
                                                <div class="p-3 rounded-lg mr-4 
                                                    @if($leave->type === 'annual') bg-blue-50 text-blue-600
                                                    @elseif($leave->type === 'sick') bg-emerald-50 text-emerald-600
                                                    @else bg-gray-50 text-gray-600 @endif">
                                                    @if($leave->type === 'annual')
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    @elseif($leave->type === 'sick')
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $leave->type_label }}</h4>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $leave->total_days }} hari kerja</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $leave->status_badge_class }}">
                                                    {{ $leave->status_label }}
                                                </span>
                                                <a href="{{ route('leave-applications.show', $leave) }}" 
                                                   class="block mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                                    Lihat Detail
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="inline-block p-6 bg-gray-50 rounded-lg">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-600">Belum ada riwayat cuti</p>
                                        <a href="{{ route('leave-applications.create') }}" 
                                           class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800">
                                            Ajukan cuti pertama Anda
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan CSS untuk animasi -->
    <style>
        .card {
            @apply bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden transition-all duration-300;
        }
        .card:hover {
            @apply shadow-xl transform -translate-y-1;
        }
        .stat-number {
            @apply text-3xl font-bold mt-1;
        }
        .section-box {
            @apply bg-white rounded-xl shadow-lg border border-gray-100 p-6;
        }
        .title-text {
            @apply text-lg font-semibold text-gray-800 mb-4;
        }
    </style>
</x-app-layout>