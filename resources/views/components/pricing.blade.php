@php
    $packages = \App\Models\SuperAdmin\Package::where('is_active', true)
        ->orderBy('price', 'asc')
        ->get();
@endphp

<section id="pricing" class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-4xl mx-auto mb-16">
            <span class="section-badge">
                Simple, Transparent Pricing
            </span>
            <h2 class="section-title">
                Choose the Perfect Plan for Your Business
            </h2>
            <p class="section-description">
                Start with a free 14-day trial. No credit card required. Cancel anytime.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 max-w-full mx-auto">
            @foreach($packages as $package)
                <x-pricing-card 
                    :title="$package->name"
                    :price="$package->price == 0 ? 'Free' : number_format($package->price, 0, ',', '.')"
                    :period="$package->duration_days . ' days'"
                    :description="$package->description"
                    :popular="$package->name === 'PROFESSIONAL' || $package->name === 'TRIAL'"
                    :features="$package->features ?? []"
                    :ctaLink="route('register', ['package' => $package->id])"
                    :ctaText="$package->price == 0 ? 'Start Free Trial' : 'Choose Plan'"
                />
            @endforeach
        </div>
    </div>
</section>
