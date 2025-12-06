<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                        Leave Management
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-base text-gray-600 hover:text-blue-600 font-medium">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @auth
                        {{-- User & Global Menu --}}
                        <x-nav-link :href="route('leave-applications.index')" :active="request()->routeIs('leave-applications.*')" class="text-base text-gray-600 hover:text-blue-600 font-medium">
                            {{ __('Leave Applications') }}
                        </x-nav-link>
                        
                        @if(auth()->user()->isLeader())
                            @php
                                $pendingCountLeader = \App\Models\LeaveApplication::pendingForLeader(auth()->user())->count();
                            @endphp
                            <x-nav-link :href="route('leader.verification.index')" :active="request()->routeIs('leader.verification.*')" class="text-base text-gray-600 hover:text-blue-600 font-medium relative">
                                {{ __('Verification') }}
                                @if($pendingCountLeader > 0)
                                    <span class="absolute top-2 right-[-10px] w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
                                    <span class="ml-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ $pendingCountLeader }}</span>
                                @endif
                            </x-nav-link>
                        @endif
                        
                        @if(auth()->user()->isHRD() || auth()->user()->isAdmin())
                            @php
                                $pendingCountHRD = \App\Models\LeaveApplication::pendingForHRD()->count();
                            @endphp
                            <x-nav-link :href="route('hrd.approval.index')" :active="request()->routeIs('hrd.approval.*')" class="text-base text-gray-600 hover:text-blue-600 font-medium relative">
                                {{ __('HRD Approval') }}
                                @if($pendingCountHRD > 0)
                                    <span class="absolute top-2 right-[-10px] w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
                                    <span class="ml-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ $pendingCountHRD }}</span>
                                @endif
                            </x-nav-link>
                        @endif
                        
                        {{-- BLOK ADMIN DROPDOWN DIHILANGKAN --}}
                        {{-- Jika Admin perlu akses ke manajemen user/divisi, mereka akan menggunakan rute langsung atau harus diakses dari dashboard Admin. --}}

                    @endauth
                </div>
            </div>
            
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-700 bg-gray-100 hover:text-gray-900 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('leave-applications.index')" :active="request()->routeIs('leave-applications.*')">
                {{ __('Leave Applications') }}
            </x-responsive-nav-link>
            
            @if(auth()->user()->isLeader())
                <x-responsive-nav-link :href="route('leader.verification.index')" :active="request()->routeIs('leader.verification.*')">
                    {{ __('Verification') }}
                </x-responsive-nav-link>
            @endif
            
            @if(auth()->user()->isHRD() || auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('hrd.approval.index')" :active="request()->routeIs('hrd.approval.*')">
                    {{ __('HRD Approval') }}
                </x-responsive-nav-link>
            @endif
            
            {{-- BLOK ADMIN RESPONSIVE DIHILANGKAN --}}
        </div>
        
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    /* * CSS Custom untuk Tampilan Bersih dan Profesional
    * Mengganti gradien mencolok dengan palet warna korporat (Putih, Abu-abu, Biru)
    */
    
    /* Global Nav Container */
    .bg-white {
        background: #ffffff !important; 
    }
    
    .border-gray-100 {
        border-color: #e5e7eb !important; 
    }
    
    /* Logo Title */
    .text-xl.font-bold.text-gray-800 {
        color: #1f2937 !important; /* Dark Grey */
        font-weight: 700;
        font-size: 1.25rem;
    }
    
    /* Desktop Nav Links */
    .space-x-2 a.text-gray-600 {
        color: #4b5563 !important; /* Medium Grey */
        padding: 0.5rem 1rem !important;
        border-radius: 6px;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
        margin-left: 0.5rem;
        margin-right: 0.5rem;
    }
    
    .space-x-2 a.text-gray-600:hover {
        color: #2563eb !important; /* Blue 600 */
        background: #f3f4f6; /* Light Grey Hover BG */
    }
    
    .space-x-2 a.active {
        color: #2563eb !important; /* Blue 600 */
        border-bottom-color: #2563eb !important; /* Active indicator */
        font-weight: 600 !important;
    }

    /* Notification Badge (Red dot/pill) */
    .bg-red-500 {
        background: #ef4444 !important;
    }
    
    /* Settings Dropdown Button */
    .sm\:items-center button {
        background: #f3f4f6 !important;
        border: 1px solid #d1d5db !important;
        border-radius: 8px;
        padding: 0.5rem 0.75rem !important;
    }
    
    .sm\:items-center button:hover {
        color: #1f2937 !important;
        background: #e5e7eb !important;
    }

    /* Admin Dropdown Menu Style (JADI DIHILANGKAN, TAPI CSS SISA DIBIARKAN) */
    .relative.group .absolute {
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1); /* Subtle Shadow */
        border-radius: 8px !important;
    }
    
    .relative.group .absolute a {
        color: #4b5563 !important;
        padding: 0.5rem 1rem !important;
        transition: all 0.2s;
    }
    
    .relative.group .absolute a:hover {
        background: #eff6ff !important; /* Blue 50 hover background */
        color: #2563eb !important;
    }
    
    /* Responsive Menu (Mobile) */
    .sm\:hidden {
        background-color: #ffffff !important; 
        border-top: 1px solid #e5e7eb;
    }
    
    .sm\:hidden .pt-2 a, .sm\:hidden .mt-3 a {
        color: #4b5563 !important;
        font-weight: 500;
    }

    .sm\:hidden .pt-2 a.active, .sm\:hidden .mt-3 a.active {
        background-color: #eff6ff !important;
        color: #2563eb !important;
        border-left: 4px solid #2563eb;
    }

    .sm\:hidden .text-gray-800, .sm\:hidden .text-gray-500 {
        color: #1f2937 !important; 
    }
</style>

<script>
    // Script untuk dropdown hover (dibiarkan, meskipun elemennya hilang)
    document.addEventListener('DOMContentLoaded', function() {
        const adminDropdown = document.querySelector('.relative.group');
        if (adminDropdown) {
            adminDropdown.addEventListener('mouseenter', function() {
                this.querySelector('.absolute').classList.remove('hidden');
            });
            
            adminDropdown.addEventListener('mouseleave', function() {
                this.querySelector('.absolute').classList.add('hidden');
            });
        }
    });
</script>