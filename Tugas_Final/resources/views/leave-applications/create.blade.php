<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Apply for Leave') }}
            </h2>
            <a href="{{ route('leave-applications.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                Back to List
            </a>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Display flash errors --}}
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('leave-applications.store') }}" enctype="multipart/form-data" id="leaveForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Leave Type *</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <input type="radio" id="annual" name="type" value="annual" class="sr-only peer" checked {{ old('type', 'annual') == 'annual' ? 'checked' : '' }}>
                                        <label for="annual" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900">Annual Leave</h3>
                                                    <p class="text-sm text-gray-500">12 days quota per year</p>
                                                    <p class="text-xs text-blue-600 mt-1" id="remainingQuota">Remaining: {{ $remainingQuota }} days</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" id="sick" name="type" value="sick" class="sr-only peer" {{ old('type') == 'sick' ? 'checked' : '' }}>
                                        <label for="sick" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-gray-900">Sick Leave</h3>
                                                    <p class="text-sm text-gray-500">With doctor's note</p>
                                                    <p class="text-xs text-green-600 mt-1">No quota reduction</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date *</label>
                                <input type="date" name="start_date" id="start_date" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        value="{{ old('start_date') }}"
                                        required>
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date *</label>
                                <input type="date" name="end_date" id="end_date" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        value="{{ old('end_date') }}"
                                        required>
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-2">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Total Working Days</p>
                                            <p class="text-xs text-gray-500">Saturday & Sunday excluded</p>
                                        </div>
                                        <div class="text-2xl font-bold text-blue-600" id="totalDays">0</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-span-2">
                                <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Leave *</label>
                                <textarea name="reason" id="reason" rows="3" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            placeholder="Please describe the reason for your leave..."
                                            required>{{ old('reason') }}</textarea>
                                @error('reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-2 {{ old('type') == 'sick' ? '' : 'hidden' }}" id="doctorNoteSection">
                                <label for="doctor_note_file" class="block text-sm font-medium text-gray-700">Doctor's Note *</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="doctor_note" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload a file</span>
                                                <input id="doctor_note" name="doctor_note" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF, JPG, PNG up to 2MB</p>
                                        <p class="text-xs text-gray-500" id="fileName"></p>
                                    </div>
                                </div>
                                @error('doctor_note')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-2">
                                <label for="address_during_leave" class="block text-sm font-medium text-gray-700">Address During Leave *</label>
                                <textarea name="address_during_leave" id="address_during_leave" rows="2" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            placeholder="Where will you be staying during your leave?"
                                            required>{{ old('address_during_leave') }}</textarea>
                                @error('address_during_leave')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-2">
                                <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Emergency Contact Number *</label>
                                <input type="text" name="emergency_contact" id="emergency_contact" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        placeholder="+62 812-3456-7890"
                                        value="{{ old('emergency_contact') }}"
                                        required>
                                @error('emergency_contact')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-2">
                                <div id="validationMessage" class="hidden p-4 rounded-lg mb-4"></div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('leave-applications.index') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="reset" 
                                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Reset
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeInputs = document.querySelectorAll('input[name="type"]');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const doctorNoteSection = document.getElementById('doctorNoteSection');
            const totalDaysElement = document.getElementById('totalDays');
            const remainingQuotaElement = document.getElementById('remainingQuota');
            const validationMessage = document.getElementById('validationMessage');
            const doctorNoteInput = document.getElementById('doctor_note');
            const fileNameElement = document.getElementById('fileName');
            const remainingQuotaValue = parseInt(remainingQuotaElement.textContent.match(/\d+/)[0]);
            
            // Set minimum dates
            const today = new Date();
            const todayISO = today.toISOString().split('T')[0];
            
            startDateInput.min = todayISO;
            endDateInput.min = todayISO;
            
            // Function to calculate working days (skips weekends)
            function calculateDays(start, end) {
                let totalDays = 0;
                let currentDate = new Date(start);
                const endDate = new Date(end);
                
                while (currentDate <= endDate) {
                    const day = currentDate.getDay();
                    // Skip Saturday (6) and Sunday (0)
                    if (day !== 0 && day !== 6) {
                        totalDays++;
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                return totalDays;
            }

            // Function to find the date 3 working days from now
            function getMinAnnualStartDate() {
                let minDate = new Date();
                let workingDaysCount = 0;

                while (workingDaysCount < 3) {
                    minDate.setDate(minDate.getDate() + 1);
                    const day = minDate.getDay();
                    if (day !== 0 && day !== 6) { // Check if it's a weekday
                        workingDaysCount++;
                    }
                }
                return minDate.toISOString().split('T')[0];
            }
            
            // Handle leave type change and initial load
            function updateFormBasedOnType() {
                const checkedType = document.querySelector('input[name="type"]:checked').value;
                const minAnnualDate = getMinAnnualStartDate();

                if (checkedType === 'sick') {
                    doctorNoteSection.classList.remove('hidden');
                    startDateInput.min = todayISO;
                    // Reset to today if previously set to future date for annual leave
                    if (startDateInput.value && new Date(startDateInput.value) > new Date(todayISO)) {
                         // Keep current value if valid, but update min
                    }
                } else { // annual
                    doctorNoteSection.classList.add('hidden');
                    // Set min date for annual leave (3 working days advance)
                    startDateInput.min = minAnnualDate;

                    // If current value is before min required date, show error/clear input
                    if (startDateInput.value && new Date(startDateInput.value) < new Date(minAnnualDate)) {
                        startDateInput.value = minAnnualDate; // Force update to minimum date
                    }
                }
                calculateAndValidate();
            }

            // Calculate days and perform frontend validation
            function calculateAndValidate() {
                const startDateValue = startDateInput.value;
                const endDateValue = endDateInput.value;
                const isAnnual = document.querySelector('input[name="type"]:checked').value === 'annual';
                
                let totalDays = 0;
                let errorMsg = '';
                
                if (startDateValue && endDateValue) {
                    const startDate = new Date(startDateValue);
                    const endDate = new Date(endDateValue);

                    if (startDate > endDate) {
                         errorMsg = 'End date must be after start date.';
                    } else {
                        totalDays = calculateDays(startDateValue, endDateValue);
                        if (totalDays === 0) {
                            errorMsg = 'Selected period includes no working days.';
                        }
                        
                        if (isAnnual) {
                            if (totalDays > remainingQuotaValue) {
                                errorMsg = `Insufficient annual leave quota! You need ${totalDays} days but only have ${remainingQuotaValue} days remaining.`;
                            }
                        }
                    }
                }

                totalDaysElement.textContent = totalDays;

                if (errorMsg) {
                    showValidation('error', errorMsg);
                } else {
                    hideValidation();
                }
            }
            
            // Handle file selection
            doctorNoteInput?.addEventListener('change', function() {
                if (this.files.length > 0) {
                    fileNameElement.textContent = this.files[0].name;
                } else {
                    fileNameElement.textContent = '';
                }
            });
            
            // Add event listeners
            typeInputs.forEach(input => input.addEventListener('change', updateFormBasedOnType));
            startDateInput.addEventListener('change', calculateAndValidate);
            endDateInput.addEventListener('change', calculateAndValidate);

            // Validation display functions
            function showValidation(type, message) {
                validationMessage.className = `p-4 rounded-lg mb-4 ${type === 'error' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`;
                validationMessage.textContent = message;
                validationMessage.classList.remove('hidden');
            }
            
            function hideValidation() {
                validationMessage.classList.add('hidden');
            }
            
            // Initial call
            updateFormBasedOnType();
        });
    </script>
    @endpush
</x-app-layout>