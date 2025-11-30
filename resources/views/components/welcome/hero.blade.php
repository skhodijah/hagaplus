<section class="hero-gradient py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Hero Content -->
            <div class="hero-content">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    Modern HR Management <span class="text-blue-600 dark:text-blue-400">Simplified</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 max-w-lg">
                    Streamline your HR processes with our all-in-one HR management solution. 
                    From recruitment to retirement, we've got you covered.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg text-center transition-colors">
                        Get Started Free
                    </a>
                    <a href="#features" class="bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-800 dark:text-white font-semibold py-3 px-8 rounded-lg border border-gray-300 dark:border-gray-600 text-center transition-colors">
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
                        src="https://images.pexels.com/photos/8962519/pexels-photo-8962519.jpeg" 
                        alt="HR Management Dashboard" 
                        class="rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 float-animation"
                    >
                    <!-- Floating elements -->
                    <div class="absolute -top-6 -left-6 bg-blue-100 dark:bg-blue-900 p-3 rounded-xl shadow-lg float-animation" style="animation-delay: 0.5s;">
                        <div class="bg-white dark:bg-blue-800 p-2 rounded-lg">
                            <i class="fas fa-users text-blue-600 dark:text-blue-300"></i>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-green-100 dark:bg-green-900 p-3 rounded-xl shadow-lg float-animation" style="animation-delay: 1s;">
                        <div class="bg-white dark:bg-green-800 p-2 rounded-lg">
                            <i class="fas fa-chart-line text-green-600 dark:text-green-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
