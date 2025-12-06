<x-app-layout>
    <x-slot name="header">
        {{-- PERBAIKAN: Memastikan flex container mengambil lebar penuh dari slot header --}}
        <div class="flex justify-between items-center w-full"> 
            {{-- PERBAIKAN: Tambahkan flex-grow pada h2 untuk memastikan ia mengambil ruang yang tersedia, 
                           dan tambahkan mr-4 untuk memastikan ada jarak dengan tombol. --}}
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex-grow mr-4">
                {{ __('Riwayat Pengajuan Cuti Saya') }}
            </h2>
            <a href="{{ route('leave-applications.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md flex-shrink-0">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Ajukan Cuti Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                <div class="p-6 text-gray-900">
                    
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6 font-medium" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 font-medium" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Daftar Aplikasi Cuti</h3>

                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Tipe Cuti
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Periode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Hari
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($applications as $application)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $application->type_label }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $application->start_date->format('d M Y') }} - {{ $application->end_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                                            {{ $application->total_days }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $application->status_badge_class }}">
                                                {{ $application->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                            <a href="{{ route('leave-applications.show', $application) }}" class="text-blue-600 hover:text-blue-800 font-bold">Detail</a>
                                            @if ($application->canBeCancelled())
                                                <button onclick="document.getElementById('cancel-form-{{ $application->id }}').classList.remove('hidden')" class="text-red-600 hover:text-red-800 font-bold">
                                                    Batalkan
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-lg bg-gray-50">
                                            Anda belum mengajukan cuti apapun.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Modals for Cancellation --}}
    @foreach ($applications as $application)
        @if ($application->canBeCancelled())
            <div id="cancel-form-{{ $application->id }}" class="fixed inset-0 bg-gray-900 bg-opacity-70 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
                <div class="relative top-1/4 mx-auto p-8 border w-full max-w-md shadow-2xl rounded-xl bg-white transform transition-transform duration-300 ease-out scale-95">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.332 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <h3 class="mt-3 text-xl font-bold text-gray-900">Batalkan Pengajuan Cuti</h3>
                        <div class="mt-4 px-2 py-3">
                            <p class="text-sm text-gray-600 mb-4">
                                Apakah Anda yakin membatalkan cuti ini ({{ $application->type_label }} pada {{ $application->start_date->format('d M') }} - {{ $application->end_date->format('d M Y') }})? Berikan alasan.
                            </p>
                            <form method="POST" action="{{ route('leave-applications.cancel', $application) }}">
                                @csrf
                                <textarea name="cancellation_reason" rows="3" class="mt-3 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm p-3" placeholder="Alasan pembatalan (Wajib)" required></textarea>
                                <div class="mt-6 flex justify-center space-x-3">
                                    <button type="submit" class="px-5 py-2 bg-red-600 text-white text-base font-bold rounded-lg shadow-lg hover:bg-red-700 transition duration-150">
                                        Konfirmasi Pembatalan
                                    </button>
                                    <button type="button" onclick="document.getElementById('cancel-form-{{ $application->id }}').classList.add('hidden')" class="px-5 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-lg hover:bg-gray-300 transition duration-150">
                                        Tutup
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</x-app-layout>