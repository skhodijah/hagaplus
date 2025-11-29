<section class="hero-gradient py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Hero Content -->
            <div class="hero-content">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    Modern HR Management <span class="text-[#008159] dark:text-[#76E47E]">Simplified</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 max-w-lg">
                    Streamline your HR processes with our all-in-one HR management solution. 
                    From recruitment to retirement, we've got you covered.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('register') }}" class="btn-primary">
                        Get Started Free
                    </a>
                    <a href="#features" class="btn-secondary">
                        Learn More
                    </a>
                </div>
                <div class="mt-8 flex items-center space-x-6">
                    <div class="flex -space-x-2">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User 1" class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-800">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User 2" class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-800">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User 3" class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-800">
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        <p class="font-medium">Trusted by 10,000+ companies</p>
                        <div class="flex items-center">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="ml-2">4.9/5 from 500+ reviews</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hero Image -->
            <div class="hero-image relative">
                <div class="relative">
                    <img 
                        src="{{ asset('images/hero-dashboard.png') }}" 
                        alt="HR Management Dashboard" 
                        class="rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 float-animation"
                        onerror="this.onerror=null; this.src='https://via.placeholder.com/800x600/f0f9ff/0c4a6e?text=HR+Dashboard'"
                    >
                    <!-- Floating elements -->
                    <div class="floating-element -top-6 -left-6 bg-[#D2FE8C]/40 dark:bg-[#008159]/40" style="animation-delay: 0.5s;">
                        <div class="icon-container">
                            <i class="fas fa-users text-[#008159] dark:text-[#76E47E]"></i>
                        </div>
                    </div>
                    <div class="floating-element -bottom-6 -right-6 bg-[#D2FE8C]/40 dark:bg-[#008159]/40" style="animation-delay: 1s;">
                        <div class="icon-container">
                            <i class="fas fa-chart-line text-[#008159] dark:text-[#76E47E]"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
