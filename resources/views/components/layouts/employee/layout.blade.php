<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @include('partials.head', ['title' => $title ?? null])
    </head>
    <body class="min-h-screen bg-neutral-100 text-neutral-900 antialiased dark:bg-neutral-900 dark:text-neutral-100">
        <header class="fixed inset-x-0 top-0 z-50 bg-white/70 backdrop-blur supports-[backdrop-filter]:bg-white/50 dark:border-neutral-800/60 dark:bg-neutral-900/50">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="grid grid-cols-2 items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-base font-semibold flex items-center gap-1">
                            <img src="{{ asset('images/Haga.png') }}" alt="Haga+ Logo" class="h-7 w-auto">
                            <h4 class="text-xl font-bold italic">Haga+</h4>
                        </span>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button id="theme-toggle" type="button" class="inline-flex h-9 items-center gap-2 rounded border border-neutral-300 bg-white px-3 text-neutral-700  hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800" aria-label="Toggle theme">
                            <span class="text-xs font-medium">Theme</span>
                            <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 hidden"><path d="M12 4V2m0 20v-2M4 12H2m20 0h-2M5.64 5.64 4.22 4.22m15.56 15.56-1.42-1.42M18.36 5.64l1.42-1.42M5.64 18.36l-1.42 1.42"/><circle cx="12" cy="12" r="5"/></svg>
                            <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 hidden"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                        </button>
                        
                        {{-- menu notifications --}}
                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded border border-neutral-300 bg-white text-neutral-700  hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800" aria-label="Notifications">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path fillRule="evenodd" d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z" clipRule="evenodd" />
                            </svg>
                        </button>
                        {{--profile button --}}
                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center overflow-hidden rounded border border-neutral-300 bg-white text-neutral-700  hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800" aria-label="Profile">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 18a7 7 0 1114 0H5z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <div class="mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-6">
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>


