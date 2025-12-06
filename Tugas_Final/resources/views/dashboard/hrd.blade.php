<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('HRD Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border-t-4 border-yellow-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500">Pending Approvals</p>
                        <p class="text-3xl font-bold text-yellow-700 mt-1">{{ $pendingApprovals }} <span class="text-base font-normal">requests</span></p>
                        <p class="text-xs text-gray-500 mt-2">Waiting for final HRD decision.</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border-t-4 border-blue-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500">Total Leave Requests (This Month)</p>
                        <p class="text-3xl font-bold text-blue-700 mt-1">{{ $monthlyLeaves }} <span class="text-base font-normal">submissions</span></p>
                        <p class="text-xs text-gray-500 mt-2">All types submitted in the current month.</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border-t-4 border-green-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500">Employees Currently On Leave</p>
                        <p class="text-3xl font-bold text-green-700 mt-1">{{ $onLeaveThisMonth->count() }} <span class="text-base font-normal">people</span></p>
                        <p class="text-xs text-gray-500 mt-2">Currently active approved leaves.</p>
                    </div>
                </div>
            </div>
            
            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Employees On Leave This Month -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Who is On Leave Now?</h3>
                    <div class="space-y-3">
                        @forelse($onLeaveThisMonth as $leave)
                            <div class="flex justify-between items-center border-b pb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $leave->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $leave->user->division?->name ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-700">{{ $leave->start_date->format('d/M') }} - {{ $leave->end_date->format('d/M') }}</p>
                                    <span class="text-xs text-green-600 font-medium">{{ $leave->type_label }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-4">No employees are currently on approved leave.</p>
                        @endforelse
                    </div>
                </div>
                
                <!-- Division Stats -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Division Headcounts</h3>
                    <div class="space-y-3">
                        @forelse($divisions as $division)
                            <div class="flex justify-between items-center border-b pb-2">
                                <p class="font-medium text-gray-900">{{ $division->name }}</p>
                                <p class="text-sm font-bold text-indigo-600">{{ $division->members_count }} <span class="text-xs font-normal text-gray-500">members</span></p>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-4">No divisions configured.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>