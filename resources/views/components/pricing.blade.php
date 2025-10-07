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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Starter Plan -->
            <x-pricing-card 
                title="Starter"
                price="9"
                period="month"
                description="Perfect for small teams getting started"
                :features="[
                    'Up to 10 employees',
                    'Basic HR features',
                    'Email support'
                ]"
            />

            <!-- Professional Plan -->
            <x-pricing-card 
                title="Professional"
                price="19"
                period="month"
                description="Best for growing businesses"
                popular
                :features="[
                    'Up to 50 employees',
                    'Advanced HR features',
                    'Priority email & chat support',
                    'Custom reporting'
                ]"
            />

            <!-- Enterprise Plan -->
            <x-pricing-card 
                title="Enterprise"
                price="Custom"
                period="month"
                description="For large organizations with complex needs"
                :features="[
                    'Unlimited employees',
                    'All Professional features',
                    '24/7 priority support',
                    'Dedicated account manager',
                    'Custom integrations',
                    'On-premise deployment'
                ]"
                ctaText="Contact Sales"
                ctaLink="#contact"
            />
        </div>
    </div>
</section>
