@extends('layouts.app')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
                Simple, transparent pricing
            </h1>
            <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-500 dark:text-gray-300">
                Choose the perfect plan for your business needs. No hidden fees, no surprises.
            </p>
        </div>

        <!-- Toggle between monthly and annual billing -->
        <div class="mt-12 flex justify-center">
            <div class="relative flex items-center bg-white dark:bg-gray-800 rounded-lg p-1 border border-gray-200 dark:border-gray-700">
                <button type="button" class="relative py-2 px-6 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 whitespace-nowrap">
                    Monthly billing
                </button>
                <button type="button" class="ml-1 relative py-2 px-6 border border-transparent text-sm font-medium text-gray-700 dark:text-gray-300 bg-transparent hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md focus:outline-none whitespace-nowrap">
                    Annual billing (Save 20%)
                </button>
            </div>
        </div>

        <!-- Pricing cards -->
        <div class="mt-16 space-y-8 lg:grid lg:grid-cols-3 lg:gap-8 lg:space-y-0">
            @foreach($packages as $package)
            <div class="relative p-8 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm flex flex-col transition-all duration-200 hover:shadow-lg hover:border-blue-500 dark:hover:border-blue-500">
                <!-- Popular Badge -->
                @if($package->is_popular)
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-4 py-1 rounded-full">Most Popular</span>
                </div>
                @endif

                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $package->name }}</h3>
                    <p class="mt-4 flex items-baseline text-gray-900 dark:text-white">
                        <span class="text-4xl font-extrabold tracking-tight">Rp{{ number_format($package->price, 0, ',', '.') }}</span>
                        <span class="ml-1 text-xl font-semibold text-gray-500 dark:text-gray-400">/month</span>
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Billed annually or {{ number_format($package->price * 1.2, 0, ',', '.') }}/month</p>
                    
                    <!-- Features -->
                    <ul role="list" class="mt-6 space-y-4">
                        <li class="flex">
                            <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Up to {{ $package->max_employees }} employees</span>
                        </li>
                        <li class="flex">
                            <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Up to {{ $package->max_branches }} branches</span>
                        </li>
                        <li class="flex">
                            <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">{{ $package->duration_days }}-day free trial</span>
                        </li>
                        @if($package->features)
                            @foreach(explode('|', $package->features) as $feature)
                            <li class="flex">
                                <svg class="flex-shrink-0 h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-3 text-gray-700 dark:text-gray-300">{{ trim($feature) }}</span>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div class="mt-8">
                    <a href="{{ route('register') }}" class="block w-full py-3 px-6 text-center rounded-md text-white bg-blue-600 hover:bg-blue-700 font-medium">
                        Get started
                    </a>
                    <p class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
                        No credit card required
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- FAQ Section -->
        <div class="mt-24">
            <h2 class="text-3xl font-extrabold text-center text-gray-900 dark:text-white">Frequently asked questions</h2>
            <div class="mt-12 max-w-3xl mx-auto divide-y-2 divide-gray-200 dark:divide-gray-700">
                <div class="py-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Can I change plans later?</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Yes, you can upgrade or downgrade your plan at any time. Your subscription will be prorated based on the remaining time in your billing cycle.</p>
                </div>
                <div class="py-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Is there a free trial available?</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Yes! All plans come with a free trial period. You can try any plan for free before committing to a paid subscription.</p>
                </div>
                <div class="py-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">What payment methods do you accept?</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">We accept all major credit cards including Visa, Mastercard, American Express, and PayPal.</p>
                </div>
                <div class="py-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">How does the free trial work?</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Your free trial starts immediately after signup and lasts for the duration specified in your plan. No credit card is required to start a free trial.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
