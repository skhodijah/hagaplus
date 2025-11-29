<nav class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center">
                    <x-application-logo class="h-10 w-auto" />
                    <span class="ml-3 text-xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="nav-link">
                    Features
                </a>
                <a href="#pricing" class="nav-link">
                    Pricing
                </a>
                <a href="#contact" class="nav-link">
                    Contact
                </a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary ml-4">
                        Get Started
                    </a>
                @endauth

                <!-- Dark mode toggle -->
                <button id="dark-mode-toggle" class="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-moon dark:hidden"></i>
                    <i class="fas fa-sun hidden dark:inline"></i>
                </button>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-gray-700 dark:text-gray-300 hover:text-[#008159] dark:hover:text-[#76E47E]">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <a href="#features" class="mobile-nav-link">
                Features
            </a>
            <a href="#pricing" class="mobile-nav-link">
                Pricing
            </a>
            <a href="#contact" class="mobile-nav-link">
                Contact
            </a>
            
            @auth
                <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-link">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="mobile-nav-link text-[#008159] dark:text-[#76E47E]">
                    Get Started
                </a>
            @endauth
        </div>
    </div>
</nav>
