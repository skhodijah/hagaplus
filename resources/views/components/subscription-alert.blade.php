@props(['status' => 'active', 'daysRemaining' => 0, 'limits' => null, 'packageName' => null, 'endDate' => null])

@php
    $alertConfig = [
        'expired' => [
            'bg' => 'bg-red-500/95',
            'icon' => 'fa-exclamation-circle',
            'iconColor' => 'text-white',
            'title' => 'Paket Berakhir',
            'message' => 'Perpanjang atau upgrade paket Anda',
        ],
        'expiring_soon' => [
            'bg' => 'bg-amber-500/95',
            'icon' => 'fa-clock',
            'iconColor' => 'text-white',
            'title' => 'Akan Berakhir',
            'message' => 'Segera perpanjang paket Anda',
        ],
        'suspended' => [
            'bg' => 'bg-gray-500/95',
            'icon' => 'fa-ban',
            'iconColor' => 'text-white',
            'title' => 'Akun Ditangguhkan',
            'message' => 'Hubungi administrator',
        ],
    ];

    $config = $alertConfig[$status] ?? null;
    $isTrial = strtoupper($packageName ?? '') === 'TRIAL';
    $primaryRoute = $isTrial ? 'admin.subscription.upgrade' : 'admin.subscription.extend';
@endphp

@if($config)
<!-- Floating Toast Notification -->
<div x-data="{ 
        show: true,
        endDate: '{{ $endDate }}',
        countdown: '',
        updateCountdown() {
            if (!this.endDate) return;
            
            const end = new Date(this.endDate).getTime();
            const now = new Date().getTime();
            const distance = end - now;
            
            if (distance < 0) {
                this.countdown = 'Expired';
                return;
            }
            
            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            this.countdown = String(hours).padStart(2, '0') + ':' + 
                           String(minutes).padStart(2, '0') + ':' + 
                           String(seconds).padStart(2, '0');
        }
     }" 
     x-init="updateCountdown(); setInterval(() => updateCountdown(), 1000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-full"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-full"
     class="fixed top-20 right-4 z-50 max-w-sm w-full"
     style="display: none;">
    
    <div class="{{ $config['bg'] }} backdrop-blur-sm text-white rounded-xl shadow-2xl overflow-hidden border border-white/20">
        <div class="p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas {{ $config['icon'] }} {{ $config['iconColor'] }} text-lg"></i>
                    </div>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold mb-0.5">{{ $config['title'] }}</h4>
                    @if($status === 'expiring_soon' && $endDate)
                        <p class="text-xs opacity-90 mb-1">{{ $config['message'] }}</p>
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-hourglass-half text-xs"></i>
                            <span class="text-sm font-mono font-bold tracking-wider" x-text="countdown">00:00:00</span>
                        </div>
                    @else
                        <p class="text-xs opacity-90 mb-2">{{ $config['message'] }}</p>
                    @endif
                    
                    <div class="flex gap-2">
                        <a href="{{ route($primaryRoute) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold transition-all duration-200">
                            <i class="fas fa-rocket text-xs"></i>
                            {{ $isTrial ? 'Upgrade' : 'Perpanjang' }}
                        </a>
                        <a href="{{ route('admin.subscription.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-medium transition-all duration-200">
                            Detail
                        </a>
                    </div>
                </div>
                
                <button @click="show = false" 
                        class="flex-shrink-0 p-1 hover:bg-white/20 rounded-lg transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- Progress bar for auto-dismiss -->
        <div class="h-1 bg-white/20">
            <div class="h-full bg-white/40 animate-shrink"></div>
        </div>
    </div>
</div>

<style>
@keyframes shrink {
    from { width: 100%; }
    to { width: 0%; }
}
.animate-shrink {
    animation: shrink 10s linear forwards;
}
</style>

<script>
// Auto-dismiss after 10 seconds
setTimeout(() => {
    const toast = document.querySelector('[x-data*="show: true"]');
    if (toast && toast.__x) {
        toast.__x.$data.show = false;
    }
}, 10000);
</script>
@endif
