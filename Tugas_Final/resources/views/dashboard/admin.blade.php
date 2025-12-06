<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-2.5a6 6 0 01-6 6m0 0a6 6 0 01-6-6m6 6V10" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Active Employees
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $activeUsers }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Leave Requests This Month
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $monthlyLeaves }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Pending Approvals
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $pendingApprovals }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Divisions
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $totalDivisions }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- New Employees -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">New Employees (< 1 Year)</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Join Date
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Division
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($newEmployees as $employee)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full" src="{{ $employee->profile_photo ? asset('storage/' . $employee->profile_photo) : asset('images/default-avatar.png') }}" alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $employee->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $employee->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $employee->join_date->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $employee->division?->name ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No new employees found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Leave Requests -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Leave Requests</h3>
                        <div class="space-y-4">
                            @forelse($pendingLeaves as $leave)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $leave->user->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $leave->type_label }} • {{ $leave->total_days }} days</p>
                                            <p class="text-sm text-gray-500">{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $leave->status_badge_class }}">
                                            {{ $leave->status_label }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">{{ Str::limit($leave->reason, 100) }}</p>
                                    <div class="mt-3 flex space-x-2">
                                        @if($leave->status === 'approved_by_leader')
                                            <a href="{{ route('hrd.approval.show', $leave) }}" class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                                Review
                                            </a>
                                        @endif
                                        <a href="{{ route('leave-applications.show', $leave) }}" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">No pending leave requests.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>