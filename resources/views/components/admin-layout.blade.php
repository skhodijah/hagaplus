@php
    $pendingRevisionsCount = \App\Models\Admin\AttendanceRevision::where('status', 'pending')->count();
    $pendingLeavesCount = \App\Models\Admin\Leave::where('status', 'pending')
        ->whereHas('user', function($query) {
            $query->where('instansi_id', Auth::user()->instansi_id);
        })
        ->count();
    $pendingReimbursementsCount = \DB::table('reimbursements')
        ->join('users', 'reimbursements.user_id', '=', 'users.id')
        ->where('reimbursements.status', 'pending')
        ->where('users.instansi_id', Auth::user()->instansi_id)
        ->count();
    $pendingSubscriptionRequest = \DB::table('subscription_requests')
        ->where('instansi_id', Auth::user()->instansi_id)
        ->whereIn('payment_status', ['pending', 'pending_verification'])
        ->exists();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HagaPlus - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/8c8ccf764d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Alpine.js x-cloak CSS -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-40 flex-shrink-0 w-72 lg:w-80 bg-white dark:bg-gray-800 shadow-xl transition-colors duration-300 sidebar-transition sidebar-closed lg:transform-none">
            <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    @if(Auth::user()->instansi && Auth::user()->instansi->logo)
                        <img src="{{ asset('storage/' . Auth::user()->instansi->logo) }}" alt="{{ Auth::user()->instansi->nama_instansi }}" class="w-8 h-8 object-contain">
                    @else
                        <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8">
                    @endif
                    <span class="text-xl font-semibold italic text-gray-900 dark:text-white">{{ Auth::user()->instansi->abbreviated_name ?? 'Haga+' }}</span>
                </a>
                <button id="sidebar-close" class="ml-auto lg:hidden p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" aria-label="Close sidebar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" x-data="{ 
                openMenu: '{{ 
                    request()->routeIs('admin.employees.*') ? 'people' : 
                    (request()->routeIs('admin.attendance.*') || request()->routeIs('admin.leaves.*') || request()->routeIs('admin.policies.*') || request()->routeIs('admin.division-policies.*') || request()->routeIs('admin.employee-policies.*') || request()->routeIs('admin.attendance-policy.*') || request()->routeIs('admin.holidays.*') ? 'attendance' : 
                    (request()->routeIs('admin.payroll.*') || request()->routeIs('admin.reimbursements.*') ? 'finance' : 
                    (request()->routeIs('admin.branches.*') || request()->routeIs('admin.organization.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.divisions.*') || request()->routeIs('admin.hierarchy.*') ? 'organization' : ''))) 
                }}'
            }">
                <!-- Dashboard -->
                <x-layout.sidebar-link :href="route('admin.dashboard')" icon="fa-solid fa-gauge" label="Dashboard" :active="request()->routeIs('admin.dashboard')" />

                <!-- People -->
                @hasAnyPermission('view-employees')
                <div class="space-y-1 pt-2">
                    <button @click="openMenu = openMenu === 'people' ? '' : 'people'" 
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group
                                   {{ request()->routeIs('admin.employees.*') 
                                      ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' 
                                      : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg mr-2 transition-colors
                                       {{ request()->routeIs('admin.employees.*') 
                                          ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-300' 
                                          : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-sm' }}">
                                <i class="fa-solid fa-users text-xs"></i>
                            </span>
                            <span>People</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 opacity-50" :class="openMenu === 'people' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'people'" x-collapse class="pl-11 pr-3 space-y-1">
                        @hasPermission('view-employees')
                        <a href="{{ route('admin.employees.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('admin.employees.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Employee List
                        </a>
                        @endhasPermission
                    </div>
                </div>
                @endhasAnyPermission

                <!-- Time & Attendance -->
                @hasAnyPermission('view-attendance', 'view-leaves', 'approve-attendance-revisions', 'manage-attendance-policy', 'manage-division-policies', 'manage-employee-policies')
                <div class="space-y-1 pt-2">
                    <button @click="openMenu = openMenu === 'attendance' ? '' : 'attendance'" 
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group
                                   {{ request()->routeIs('admin.attendance.*') || request()->routeIs('admin.leaves.*') || request()->routeIs('admin.policies.*') || request()->routeIs('admin.division-policies.*') || request()->routeIs('admin.employee-policies.*') || request()->routeIs('admin.attendance-policy.*') || request()->routeIs('admin.holidays.*')
                                      ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' 
                                      : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg mr-2 transition-colors
                                       {{ request()->routeIs('admin.attendance.*') || request()->routeIs('admin.leaves.*') || request()->routeIs('admin.policies.*') || request()->routeIs('admin.division-policies.*') || request()->routeIs('admin.employee-policies.*') || request()->routeIs('admin.attendance-policy.*') || request()->routeIs('admin.holidays.*')
                                          ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-300' 
                                          : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-sm' }}">
                                <i class="fa-solid fa-calendar-check text-xs"></i>
                            </span>
                            <span>Time & Attendance</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($pendingRevisionsCount > 0 || $pendingLeavesCount > 0)
                                <span class="inline-flex items-center justify-center w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 opacity-50" :class="openMenu === 'attendance' ? 'rotate-180' : ''"></i>
                        </div>
                    </button>
                    <div x-show="openMenu === 'attendance'" x-collapse class="pl-11 pr-3 space-y-1">
                        @hasPermission('view-attendance')
                        <a href="{{ route('admin.attendance.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('admin.attendance.index') || request()->routeIs('admin.attendance.show') || request()->routeIs('admin.attendance.employee') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Daily Logs
                        </a>
                        @endhasPermission
                        @hasPermission('approve-attendance-revisions')
                        <div class="relative">
                            <a href="{{ route('admin.attendance.revisions.index') }}" 
                               class="block px-3 py-2 text-sm rounded-md transition-colors duration-200 flex justify-between items-center
                                      {{ request()->routeIs('admin.attendance.revisions.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                                <span>Revision Requests</span>
                                @if($pendingRevisionsCount > 0)
                                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-red-100 bg-red-500 rounded-full">{{ $pendingRevisionsCount }}</span>
                                @endif
                            </a>
                        </div>
                        @endhasPermission
                        @hasPermission('view-leaves')
                        <a href="{{ route('admin.leaves.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200 flex justify-between items-center
                                  {{ request()->routeIs('admin.leaves.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <span>Leave Requests</span>
                            @if($pendingLeavesCount > 0)
                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-red-100 bg-red-500 rounded-full">{{ $pendingLeavesCount }}</span>
                            @endif
                        </a>
                        @endhasPermission
                        
                        <a href="{{ route('admin.policies.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('admin.policies.*') || request()->routeIs('admin.division-policies.*') || request()->routeIs('admin.employee-policies.*') || request()->routeIs('admin.attendance-policy.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Policies
                        </a>
                        
                        <a href="{{ route('admin.holidays.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('admin.holidays.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Holidays
                        </a>
                    </div>
                </div>
                @endhasAnyPermission

                <!-- Finance -->
                @hasAnyPermission('view-payroll', 'view-reimbursements')
                <div class="space-y-1 pt-2">
                    <button @click="openMenu = openMenu === 'finance' ? '' : 'finance'" 
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group
                                   {{ request()->routeIs('admin.payroll.*') || request()->routeIs('admin.reimbursements.*') 
                                      ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' 
                                      : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg mr-2 transition-colors
                                       {{ request()->routeIs('admin.payroll.*') || request()->routeIs('admin.reimbursements.*') 
                                          ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-300' 
                                          : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-sm' }}">
                                <i class="fa-solid fa-money-bill-wave text-xs"></i>
                            </span>
                            <span>Finance</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($pendingReimbursementsCount > 0)
                                <span class="inline-flex items-center justify-center w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 opacity-50" :class="openMenu === 'finance' ? 'rotate-180' : ''"></i>
                        </div>
                    </button>
                    <div x-show="openMenu === 'finance'" x-collapse class="pl-11 pr-3 space-y-1">
                        @hasPermission('view-payroll')
                        <a href="{{ route('admin.payroll.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('admin.payroll.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Payroll
                        </a>
                        @endhasPermission
                        @hasPermission('view-reimbursements')
                        <a href="{{ route('admin.reimbursements.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200 flex justify-between items-center
                                  {{ request()->routeIs('admin.reimbursements.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <span>Reimbursements</span>
                            @if($pendingReimbursementsCount > 0)
                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-red-100 bg-red-500 rounded-full">{{ $pendingReimbursementsCount }}</span>
                            @endif
                        </a>
                        @endhasPermission
                        
                        @hasPermission('view-payroll')
                        <a href="{{ route('admin.tax-forms.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('admin.tax-forms.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            SPT 1721-A1
                        </a>
                        @endhasPermission
                    </div>
                </div>
                @endhasAnyPermission

                <!-- Organization -->
                @hasAnyPermission('manage-branches', 'manage-roles', 'manage-divisions', 'manage-departments', 'manage-positions')
                <div class="space-y-1 pt-2">
                    <button @click="openMenu = openMenu === 'organization' ? '' : 'organization'" 
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group
                                   {{ request()->routeIs('admin.branches.*') || request()->routeIs('admin.organization.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.divisions.*') || request()->routeIs('admin.hierarchy.*')
                                      ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' 
                                      : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg mr-2 transition-colors
                                       {{ request()->routeIs('admin.branches.*') || request()->routeIs('admin.organization.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.divisions.*') || request()->routeIs('admin.hierarchy.*')
                                          ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-300' 
                                          : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-sm' }}">
                                <i class="fa-solid fa-sitemap text-xs"></i>
                            </span>
                            <span>Organization</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 opacity-50" :class="openMenu === 'organization' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openMenu === 'organization'" x-collapse class="pl-11 pr-3 space-y-1">
                        @hasPermission('manage-branches')
                        <a href="{{ route('admin.branches.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('admin.branches.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Branches
                        </a>
                        @endhasPermission
                        
                        @hasAnyPermission('manage-roles', 'manage-divisions', 'manage-departments', 'manage-positions')
                        <a href="{{ route('admin.organization.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('admin.organization.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.divisions.*') || request()->routeIs('admin.hierarchy.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Structure & Roles
                        </a>
                        @endhasAnyPermission

                        @hasPermission('manage-roles')
                        <a href="{{ route('admin.admins.index') }}" 
                           class="block px-3 py-2 text-sm rounded-md transition-colors duration-200
                                  {{ request()->routeIs('admin.admins.*') ? 'text-blue-600 font-medium bg-blue-50/50 dark:text-blue-400 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            Manage Admins
                        </a>
                        @endhasPermission
                    </div>
                </div>
                @endhasAnyPermission

                <!-- Settings -->
                <div class="pt-2">
                    @if(!Auth::user()->employee)
                <x-layout.sidebar-link 
                    :href="route('admin.company-profile.index')" 
                    icon="fa-solid fa-building" 
                    label="Company Profile" 
                    :active="request()->routeIs('admin.company-profile.*')" 
                />
                @endif

</div>
            </nav>

            <!-- Subscription Status Footer -->
            <div class="mt-auto p-4 border-t border-gray-200 dark:border-gray-700">
                @php
                    // Get the latest subscription regardless of status to show correct state (Active/Expired)
                    $sidebarSubscription = \App\Models\SuperAdmin\Subscription::with('package')
                        ->where('instansi_id', Auth::user()->instansi_id ?? 1)
                        ->latest('end_date')
                        ->first();

                    $sidebarEndDate = $sidebarSubscription ? $sidebarSubscription->end_date : null;
                    $sidebarPackageName = $sidebarSubscription ? $sidebarSubscription->package->name : null;
                    
                    // Calculate days left: Positive = Future, Negative = Past
                    $sidebarDaysLeft = $sidebarEndDate ? now()->diffInDays(\Carbon\Carbon::parse($sidebarEndDate), false) : 0;
                    
                    // Check status
                    $sidebarIsExpired = $sidebarSubscription && now()->gt($sidebarEndDate);
                    $sidebarIsExpiringSoon = !$sidebarIsExpired && $sidebarDaysLeft <= 7;
                    $sidebarIsActive = $sidebarSubscription && !$sidebarIsExpired;
                @endphp

                <a href="{{ route('admin.subscription.index') }}"
                   class="flex items-center justify-between px-4 py-3 text-sm rounded-lg transition-all duration-200
                          {{ request()->routeIs('admin.subscription.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <i class="fa-solid fa-crown mr-3 {{ $sidebarIsActive ? 'text-yellow-500' : 'text-gray-400' }}"></i>
                        <div class="flex flex-col">
                            <span class="font-medium">Subscription</span>
                            @if($sidebarSubscription)
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $sidebarPackageName }}</span>
                            @else
                                <span class="text-xs text-gray-500 dark:text-gray-400">Tidak aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @php
                            $pendingPayments = \DB::table('subscription_requests')
                                ->where('instansi_id', Auth::user()->instansi_id ?? 1)
                                ->where('payment_status', 'pending')
                                ->count();
                        @endphp
                        @if($pendingPayments > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                                {{ $pendingPayments }} Pending
                            </span>
                        @endif
                        @if($sidebarSubscription)
                            @if($sidebarIsExpired)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                    Expired
                                </span>
                            @elseif($sidebarIsExpiringSoon)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                    Expiring Soon
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    Active
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                Inactive
                            </span>
                        @endif
                    </div>
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white dark:bg-gray-800  transition-colors duration-300">
                <div class="flex items-center justify-between px-4 sm:px-6 h-16">
                    <div class="flex items-center space-x-3">
                        <!-- logo small screen -->

                        <button id="sidebar-toggle" class="lg:hidden p-2 rounded text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Open sidebar">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        @if(Auth::user()->instansi && Auth::user()->instansi->logo)
                            <img src="{{ asset('storage/' . Auth::user()->instansi->logo) }}" alt="{{ Auth::user()->instansi->nama_instansi }}" class="w-8 h-8 lg:hidden">
                        @else
                            <img src="{{ asset('images/Haga.png') }}" alt="Haga+" class="w-8 h-8 lg:hidden">
                        @endif

                        <!-- logo large screen -->
                        <div class="hidden sm:block">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                </div>
                                <input type="search" placeholder="Search or type command..." class="block w-full sm:w-80 pl-10 pr-3 py-2 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-haga-2)] focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <button data-theme-toggle class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Toggle Theme">
                            <span class="block dark:hidden"><i class="fa-solid fa-moon"></i></span>
                            <span class="hidden dark:block"><i class="fa-solid fa-sun"></i></span>
                        </button>

                        <a href="#" class="hidden sm:inline-flex p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Recent Activities"><i class="fa-solid fa-clock"></i></a>
                        
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }" 
                             x-init="
                                open = false;
                                window.addEventListener('beforeunload', () => open = false);
                                document.addEventListener('click', function(e) {
                                    const clicked = e.target;
                                    if (clicked.closest('button#prev-month') || clicked.closest('button#next-month') || 
                                        (clicked.tagName === 'A' && clicked.href && clicked.href.includes('month='))) {
                                        open = false;
                                    }
                                });
                             ">
                            <button @click="open = !open" class="inline-flex p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" title="Profile Settings">
                                <i class="fa-solid fa-user"></i>
                            </button>
                            
                            <!-- Profile Dropdown Menu -->
                            <div x-show="open" 
                                 x-cloak
                                 @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95">
                                
                                <!-- Profile Info -->
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-user text-white"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Menu Items -->
                                <div class="py-1">
                                    @if(!Auth::user()->employee)
                                        <a href="{{ route('admin.company-profile.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <i class="fa-solid fa-building mr-3 text-gray-400"></i>
                                            Company Profile
                                        </a>
                                    @endif
                                    @if(Auth::user()->employee)
                                        <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <i class="fa-solid fa-id-card mr-3 text-gray-400"></i>
                                            Switch to Employee View
                                        </a>
                                    @endif
                                    <a href="#" onclick="alert('Help & Support feature coming soon!')" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                        <i class="fa-solid fa-question-circle mr-3 text-gray-400"></i>
                                        Help & Support
                                    </a>
                                </div>
                                
                                <!-- Logout -->
                                <div class="border-t border-gray-200 dark:border-gray-700 py-1">
                                    <a href="#" data-logout class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <i class="fa-solid fa-sign-out-alt mr-3"></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>


            @include('partials.breadcrumbs')

            @php
                $subscriptionService = new \App\Services\SubscriptionService(Auth::user()->instansi);
                $subscriptionStatus = $subscriptionService->getSubscriptionStatus();
                $daysRemaining = $subscriptionService->getRemainingDays();
                $limits = $subscriptionService->getLimits();
                $activeSubscription = $subscriptionService->getActiveSubscription();
                $packageName = $activeSubscription ? $activeSubscription->package->name : null;
                $endDate = $activeSubscription ? $activeSubscription->end_date : null;
            @endphp

            @if(in_array($subscriptionStatus, ['expired', 'expiring_soon', 'suspended']))
                <x-subscription-alert 
                    :status="$subscriptionStatus" 
                    :daysRemaining="$daysRemaining"
                    :limits="$limits"
                    :packageName="$packageName"
                    :endDate="$endDate"
                />
            @endif

            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
                <div class="mx-auto px-4 sm:px-6 py-6 sm:py-8 max-w-7xl">

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div x-data="{ 
        showDeleteModal: false, 
        deleteForm: null,
        itemName: '',
        modalTitle: 'Confirm Delete',
        modalMessage: 'Are you sure you want to delete this item?',
        openDeleteModal(form, name = '', title = 'Confirm Delete', message = '') {
            this.deleteForm = form;
            this.itemName = name;
            this.modalTitle = title;
            this.modalMessage = message || `Are you sure you want to delete ${name ? '<strong>' + name + '</strong>' : 'this item'}? This action cannot be undone.`;
            this.showDeleteModal = true;
        },
        confirmDelete() {
            if (this.deleteForm) {
                this.deleteForm.submit();
            }
        }
    }" @open-delete-modal.window="openDeleteModal($event.detail.form, $event.detail.name, $event.detail.title, $event.detail.message)">
        <div x-show="showDeleteModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true"
             @keydown.escape.window="showDeleteModal = false">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showDeleteModal = false"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showDeleteModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title" x-text="modalTitle">
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400" x-html="modalMessage">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                        <button type="button" 
                                @click="confirmDelete(); showDeleteModal = false"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors duration-200">
                            <i class="fa-solid fa-trash mr-2"></i>
                            Delete
                        </button>
                        <button type="button" 
                                @click="showDeleteModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto transition-colors duration-200">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fancybox JS -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    
    @stack('scripts')
    
    <script>
        // Initialize Fancybox globally
        document.addEventListener('DOMContentLoaded', function() {
            Fancybox.bind('[data-fancybox]', {
                Toolbar: {
                    display: {
                        left: ["infobar"],
                        middle: [],
                        right: ["slideshow", "download", "thumbs", "close"],
                    },
                },
            });

            // Convert all confirm() dialogs to modal
            // Handle forms with onsubmit confirm
            document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
                const onsubmitAttr = form.getAttribute('onsubmit');
                if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                    // Extract message from confirm()
                    const match = onsubmitAttr.match(/confirm\(['"](.+?)['"]\)/);
                    const message = match ? match[1] : 'Are you sure you want to delete this item?';
                    
                    // Remove onsubmit attribute
                    form.removeAttribute('onsubmit');
                    
                    // Add click handler to submit button
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.type = 'button';
                        submitBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            window.dispatchEvent(new CustomEvent('open-delete-modal', {
                                detail: {
                                    form: form,
                                    name: '',
                                    title: 'Confirm Delete',
                                    message: message
                                }
                            }));
                        });
                    }
                }
            });

            // Handle buttons with onclick confirm
            document.querySelectorAll('button[onclick*="confirm"], a[onclick*="confirm"]').forEach(btn => {
                const onclickAttr = btn.getAttribute('onclick');
                if (onclickAttr && onclickAttr.includes('confirm(')) {
                    // Extract message from confirm()
                    const match = onclickAttr.match(/confirm\(['"](.+?)['"]\)/);
                    const message = match ? match[1] : 'Are you sure you want to delete this item?';
                    
                    // Remove onclick attribute
                    btn.removeAttribute('onclick');
                    
                    // Get the form if button is inside a form
                    const form = btn.closest('form');
                    
                    if (form) {
                        btn.type = 'button';
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            window.dispatchEvent(new CustomEvent('open-delete-modal', {
                                detail: {
                                    form: form,
                                    name: '',
                                    title: 'Confirm Delete',
                                    message: message
                                }
                            }));
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>