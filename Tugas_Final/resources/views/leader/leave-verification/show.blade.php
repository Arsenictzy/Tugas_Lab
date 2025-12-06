<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-800 leading-tight">
                    Detail <span class="text-indigo-600">Verifikasi Cuti</span>
                </h2>
                <p class="text-gray-600 mt-2">Review pengajuan cuti dari anggota</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('leader.verification.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Status Alert -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-emerald-700 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Leave Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Detail Pengajuan Cuti</h3>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $leaveApplication->status_badge_class }}">
                                    {{ $leaveApplication->status_label }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <!-- Employee Info -->
                            <div class="mb-8">
                                <h4 class="text-sm font-medium text-gray-600 mb-3">Informasi Karyawan</h4>
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <div class="w-16 h-16 rounded-full overflow-hidden mr-4">
                                        <img class="w-full h-full object-cover" 
                                             src="{{ $leaveApplication->user->profile_photo ? asset('storage/' . $leaveApplication->user->profile_photo) : asset('images/default-avatar.png') }}" 
                                             alt="{{ $leaveApplication->user->name }}">
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-gray-900">{{ $leaveApplication->user->name }}</h5>
                                        <p class="text-sm text-gray-600">{{ $leaveApplication->user->email }}</p>
                                        <div class="flex items-center mt-1">
                                            <span class="text-xs px-2 py-1 bg-indigo-100 text-indigo-700 rounded">
                                                {{ $leaveApplication->user->division?->name ?? 'Belum Ditugaskan' }}
                                            </span>
                                            <span class="ml-2 text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                {{ ucfirst($leaveApplication->user->role) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Leave Details -->
                            <div class="space-y-6">
                                <!-- Basic Info -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-600 mb-3">Informasi Cuti</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-gray-500 mb-1">Jenis Cuti</p>
                                            <p class="font-medium text-gray-900">{{ $leaveApplication->type_label }}</p>
                                        </div>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-gray-500 mb-1">Durasi</p>
                                            <p class="font-medium text-gray-900">{{ $leaveApplication->total_days }} hari kerja</p>
                                        </div>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                                            <p class="font-medium text-gray-900">{{ $leaveApplication->start_date->format('d M Y') }}</p>
                                        </div>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-gray-500 mb-1">Tanggal Selesai</p>
                                            <p class="font-medium text-gray-900">{{ $leaveApplication->end_date->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reason -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-600 mb-3">Alasan Cuti</h4>
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 whitespace-pre-line">{{ $leaveApplication->reason }}</p>
                                    </div>
                                </div>

                                <!-- Supporting Documents -->
                                @if($leaveApplication->attachment_path)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-600 mb-3">Dokumen Pendukung</h4>
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Dokumen Terlampir</p>
                                                    <a href="{{ asset('storage/' . $leaveApplication->attachment_path) }}" 
                                                       target="_blank"
                                                       class="text-sm text-blue-600 hover:text-blue-800">
                                                        Lihat Dokumen
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Actions & Notes -->
                <div class="space-y-6">
                    <!-- Action Card -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Tindakan</h3>
                        </div>
                        <div class="p-6">
                            @if($leaveApplication->status === 'pending')
                                <div class="space-y-4">
                                    <!-- Approve Form -->
                                    <form action="{{ route('leader.verification.approve', $leaveApplication) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="approve_note" class="block text-sm font-medium text-gray-700 mb-2">
                                                Catatan Persetujuan (Opsional)
                                            </label>
                                            <textarea id="approve_note" name="note" 
                                                      rows="3"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                                      placeholder="Tambahkan catatan persetujuan..."></textarea>
                                        </div>
                                        <button type="submit"
                                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-emerald-500 text-white font-semibold rounded-lg hover:bg-emerald-600 transition duration-300">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Setujui Pengajuan
                                        </button>
                                    </form>

                                    <!-- Reject Form -->
                                    <form action="{{ route('leader.verification.reject', $leaveApplication) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="reject_note" class="block text-sm font-medium text-gray-700 mb-2">
                                                Alasan Penolakan (Wajib)
                                            </label>
                                            <textarea id="reject_note" name="note" 
                                                      rows="3"
                                                      required
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                      placeholder="Berikan alasan penolakan..."></textarea>
                                            @error('note')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit"
                                                onclick="return confirm('Anda yakin ingin menolak pengajuan ini?')"
                                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition duration-300">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Tolak Pengajuan
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="inline-block p-6 bg-gray-50 rounded-lg">
                                        @if($leaveApplication->status === 'approved_by_leader')
                                            <div class="w-16 h-16 mx-auto mb-4 bg-emerald-100 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="font-medium text-gray-900">Sudah Disetujui</p>
                                            <p class="text-sm text-gray-600 mt-1">Pengajuan ini sudah diverifikasi</p>
                                        @elseif($leaveApplication->status === 'rejected_by_leader')
                                            <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="font-medium text-gray-900">Sudah Ditolak</p>
                                            <p class="text-sm text-gray-600 mt-1">Pengajuan ini sudah ditolak</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes & History -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Catatan & Riwayat</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Created At -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Diajukan pada</p>
                                        <p class="text-sm text-gray-600">{{ $leaveApplication->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>

                                <!-- Last Updated -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Terakhir diupdate</p>
                                        <p class="text-sm text-gray-600">{{ $leaveApplication->updated_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>

                                <!-- Leader Note -->
                                @if($leaveApplication->leader_note)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <div class="w-8 h-8 rounded-full {{ $leaveApplication->status === 'approved_by_leader' ? 'bg-emerald-100' : 'bg-red-100' }} flex items-center justify-center">
                                                <svg class="w-4 h-4 {{ $leaveApplication->status === 'approved_by_leader' ? 'text-emerald-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                @if($leaveApplication->status === 'approved_by_leader')
                                                    Catatan Persetujuan
                                                @else
                                                    Alasan Penolakan
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-600">{{ $leaveApplication->leader_note }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>