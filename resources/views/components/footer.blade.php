<footer class="bg-gray-900 text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-12 mb-12">
            <!-- Company Info -->
            <div class="col-span-2">
                <div class="flex items-center mb-6">
                    <x-application-logo class="h-8 w-auto text-white" />
                    <span class="ml-3 text-xl font-bold">{{ config('app.name') }}</span>
                </div>
                <p class="text-gray-400 mb-6">
                    Empowering businesses with modern HR solutions to manage their workforce efficiently and effectively.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">Facebook</span>
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">Twitter</span>
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">Instagram</span>
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">LinkedIn</span>
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Product Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Product</h3>
                <ul class="space-y-3">
                    <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Features</a></li>
                    <li><a href="#pricing" class="text-gray-400 hover:text-white transition-colors">Pricing</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Integrations</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Updates</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Roadmap</a></li>
                </ul>
            </div>

            <!-- Resources -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Resources</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Documentation</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Guides</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Webinars</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Help Center</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Company</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Careers</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Partners</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Press</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Legal</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Cookie Policy</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">GDPR</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">CCPA</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">
                        Privacy
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">
                        Terms
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">
                        Cookies
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
