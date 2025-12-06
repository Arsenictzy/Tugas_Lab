<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Leave Application Details') }}
            </h2>
            <a href="{{ route('leave-applications.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="border-b border-gray-200 pb-4 mb-4">
                    <h3 class="text-2xl font-bold text-gray-800">
                        {{ $leaveApplication->type_label }} Request
                    </h3>
                    <p class="text-sm text-gray-500">
                        Submitted on: {{ $leaveApplication->application_date->format('d F Y') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-sm">
                    <!-- Column 1 -->
                    <div>
                        <p class="font-medium text-gray-700">Applicant:</p>
                        <p class="text-gray-900">{{ $leaveApplication->user->name }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">Division:</p>
                        <p class="text-gray-900">{{ $leaveApplication->user->division?->name ?? 'N/A' }}</p>
                    </div>

                    <!-- Column 2 -->
                    <div>
                        <p class="font-medium text-gray-700">Start Date:</p>
                        <p class="text-gray-900">{{ $leaveApplication->start_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">End Date:</p>
                        <p class="text-gray-900">{{ $leaveApplication->end_date->format('d M Y') }}</p>
                    </div>
                    
                    <!-- Column 3 -->
                    <div>
                        <p class="font-medium text-gray-700">Total Days (Working):</p>
                        <p class="text-lg font-bold text-blue-600">{{ $leaveApplication->total_days }} days</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-700">Status:</p>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $leaveApplication->status_badge_class }}">
                            {{ $leaveApplication->status_label }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <p class="font-medium text-gray-700">Reason:</p>
                    <p class="mt-1 p-3 bg-gray-50 rounded-md text-gray-800 italic">{{ $leaveApplication->reason }}</p>
                </div>
                
                @if ($leaveApplication->cancellation_reason)
                    <div class="mt-4">
                        <p class="font-medium text-red-700">Cancellation Reason:</p>
                        <p class="mt-1 p-3 bg-red-50 rounded-md text-red-800 italic">{{ $leaveApplication->cancellation_reason }}</p>
                    </div>
                @endif
                
                <!-- Notes and Attachments -->
                <div class="mt-6 pt-4 border-t border-gray-200 space-y-4">
                    <p class="font-bold text-lg text-gray-800">Approval Flow</p>

                    <!-- Leader Approval -->
                    <div class="border border-gray-100 rounded-lg p-4">
                        <p class="font-medium text-gray-700">Division Leader ({{ $leaveApplication->leader?->name ?? 'N/A' }})</p>
                        @if ($leaveApplication->leader_action_at)
                            <p class="text-sm text-gray-500">Actioned at: {{ $leaveApplication->leader_action_at->format('d M Y H:i') }}</p>
                            <p class="mt-2 text-sm text-gray-800">Note: {{ $leaveApplication->leader_note ?? 'No specific note provided.' }}</p>
                        @else
                            <p class="text-sm text-yellow-600 mt-2">Status: Pending Leader Review.</p>
                        @endif
                    </div>
                    
                    <!-- HRD Approval -->
                    <div class="border border-gray-100 rounded-lg p-4">
                        <p class="font-medium text-gray-700">HRD ({{ $leaveApplication->hrd?->name ?? 'N/A' }})</p>
                        @if ($leaveApplication->hrd_action_at)
                            <p class="text-sm text-gray-500">Actioned at: {{ $leaveApplication->hrd_action_at->format('d M Y H:i') }}</p>
                            <p class="mt-2 text-sm text-gray-800">Note: {{ $leaveApplication->hrd_note ?? 'No specific note provided.' }}</p>
                        @else
                            <p class="text-sm text-yellow-600 mt-2">Status: Waiting for Final HRD Approval.</p>
                        @endif
                    </div>

                    @if ($leaveApplication->doctor_note)
                        <div class="mt-4">
                            <p class="font-medium text-gray-700">Doctor's Note (Attachment):</p>
                            <a href="{{ asset('storage/' . $leaveApplication->doctor_note) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101M16.828 10.172l4 4a4 4 0 01-5.656 5.656l-4-4a4 4 0 010-5.656" />
                                </svg>
                                View Attached File
                            </a>
                        </div>
                    @endif
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>