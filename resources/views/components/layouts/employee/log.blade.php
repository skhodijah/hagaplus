@props([
    'type' => 'check-in', // check-in, check-out, break, overtime
    'time' => '',
    'date' => '',
    'badge' => 'default', // default, late, early, on-time, overtime
    'status' => '',
    'penalty' => null,
    'dotColor' => 'neutral' // neutral, green, red, orange, blue
])

@php
    // Konfigurasi badge berdasarkan status
    $badgeClasses = match($badge) {
        'late' => 'text-red-700 bg-red-100 dark:text-red-400 dark:bg-red-900/30',
        'early' => 'text-blue-700 bg-blue-100 dark:text-blue-400 dark:bg-blue-900/30', 
        'on-time' => 'text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900/30',
        'overtime' => 'text-orange-700 bg-orange-100 dark:text-orange-400 dark:bg-orange-900/30',
        default => 'text-neutral-700 bg-neutral-100 dark:text-neutral-400 dark:bg-neutral-900/30'
    };

    // Konfigurasi warna dot
    $dotClasses = match($dotColor) {
        'green' => 'bg-green-500',
        'red' => 'bg-red-500', 
        'orange' => 'bg-orange-500',
        'blue' => 'bg-blue-500',
        default => 'bg-neutral-400'
    };

    // Konfigurasi icon berdasarkan type
    $activityIcons = [
        'check-in' => 'fas fa-sign-in-alt',
        'check-out' => 'fas fa-sign-out-alt',
        'break' => 'fas fa-coffee',
        'overtime' => 'fas fa-clock'
    ];

    // Text berdasarkan type
    $activityTexts = [
        'check-in' => 'Check-in',
        'check-out' => 'Check-out', 
        'break' => 'Break Time',
        'overtime' => 'Overtime'
    ];
@endphp

<li class="rounded-lg border border-neutral-200 p-3 dark:border-neutral-700 transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
    {{-- Layout Check-in/Check-out --}}
    @if(in_array($type, ['check-in', 'check-out']))
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-3">
                {{-- Status Dot --}}
                <span class="mt-1 inline-block h-2.5 w-2.5 rounded-full {{ $dotClasses }}"></span>
                
                {{-- Activity Info --}}
                <div>
                    <p class="font-medium text-neutral-900 dark:text-neutral-100 gap-2">
                        <i class="{{ $activityIcons[$type] ?? 'fas fa-circle' }} text-sm"></i>
                        {{ $activityTexts[$type] ?? ucfirst($type) }}
                    </p>
                    <p class="text-xs text-neutral-500">
                        {{ $time }} • {{ $date }}
                    </p>
                </div>
            </div>
            
            {{-- Badge & Penalty --}}
            <div class="text-right">
                @if($status)
                    <span class="rounded-md px-2 py-0.5 text-xs font-medium {{ $badgeClasses }}">
                        {{ $status }}
                    </span>
                @endif
                
                @if($penalty)
                    <div class="mt-1 text-xs text-rose-500">
                        -{{ $penalty }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Layout Break Time --}}
    @if($type === 'break')
        <div class="flex items-center justify-between">
            <div class="flex gap-3">
                <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-orange-100 dark:bg-orange-900/30">
                </span>
                
                <div>
                    <p class="font-medium text-neutral-900 dark:text-neutral-100">Break Time</p>
                    <p class="text-xs text-neutral-500">{{ $time }} • Duration: {{ $status ?? '30 min' }}</p>
                </div>
            </div>
            
            @if($badge !== 'default')
                <span class="rounded-md px-2 py-1 text-xs font-medium {{ $badgeClasses }}">
                    {{ ucfirst($badge) }}
                </span>
            @endif
        </div>
    @endif

    {{-- Layout Overtime --}}
    @if($type === 'overtime')
        <div class="rounded-md bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 p-3 -m-3">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-3">
                    <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-orange-500 text-white">
                    </span>
                    
                    <div>
                        <p class="font-semibold text-orange-900 dark:text-orange-100">Overtime Work</p>
                        <p class="text-sm text-orange-700 dark:text-orange-300">{{ $time }} • {{ $date }}</p>
                        @if($status)
                            <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                                Duration: {{ $status }}
                            </p>
                        @endif
                    </div>
                </div>
                
                <div class="text-right">
                    <span class="rounded-md bg-orange-600 px-2 py-1 text-xs font-medium text-white">
                        Overtime
                    </span>
                    @if($penalty)
                        <div class="mt-1 text-xs text-green-600 dark:text-green-400">
                            +{{ $penalty }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Layout Custom/Default --}}
    @if(!in_array($type, ['check-in', 'check-out', 'break', 'overtime']))
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-3">
                <span class="mt-1 inline-block h-2.5 w-2.5 rounded-full {{ $dotClasses }}"></span>
                
                <div>
                    <p class="font-medium text-neutral-900 dark:text-neutral-100">
                        {{ ucfirst(str_replace('-', ' ', $type)) }}
                    </p>
                    <p class="text-xs text-neutral-500">{{ $time }} • {{ $date }}</p>
                </div>
            </div>
            
            @if($status)
                <span class="rounded-md px-2 py-0.5 text-xs font-medium {{ $badgeClasses }}">
                    {{ $status }}
                </span>
            @endif
        </div>
    @endif
</li>