<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Laporan Global Aplikasi Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">
                
                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Semua Aplikasi Cuti ({{ $applications->total() }} data)</h3>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin.reports.leave-report') }}" class="mb-6 p-4 bg-gray-50 rounded-lg shadow-inner flex flex-wrap gap-4 items-center">
                    
                    <div class="flex-1 min-w-[150px]">
                        <label for="user_id" class="block text-xs font-medium text-gray-700">Filter Karyawan</label>
                        <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                            <option value="">-- Semua Karyawan --</option>
                            @foreach ($allUsers as $user)
                                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 min-w-[120px]">
                        <label for="status" class="block text-xs font-medium text-gray-700">Filter Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                            <option value="">-- Semua Status --</option>
                            @foreach (['approved', 'pending', 'approved_by_leader', 'rejected', 'cancelled'] as $status)
                                <option value="{{ $status }}" @selected(request('status') == $status)>
                                    {{ Str::title(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 min-w-[120px]">
                        <label for="type" class="block text-xs font-medium text-gray-700">Filter Tipe</label>
                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                            <option value="">-- Semua Tipe --</option>
                            <option value="annual" @selected(request('type') == 'annual')>Cuti Tahunan</option>
                            <option value="sick" @selected(request('type') == 'sick')>Cuti Sakit</option>
                        </select>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition">
                            Filter
                        </button>
                        <a href="{{ route('admin.reports.leave-report') }}" class="px-4 py-2 border border-gray-300 rounded-lg shadow-md hover:bg-gray-100 transition">
                            Reset
                        </a>
                    </div>
                </form>

                <!-- Application Table -->
                <div class="overflow-x-auto shadow-md rounded-lg border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Karyawan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Tipe
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
                                    Detail
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($applications as $application)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $application->user->name }}
                                        <p class="text-xs text-gray-500">{{ $application->user->division->name ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{-- Gunakan rute show cuti biasa, karena admin punya Policy untuk melihat semua --}}
                                        <a href="{{ route('leave-applications.show', $application) }}" class="text-indigo-600 hover:text-indigo-800 font-bold">Lihat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-lg bg-gray-50">
                                        Tidak ada data aplikasi cuti yang ditemukan sesuai filter.
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
</x-app-layout>