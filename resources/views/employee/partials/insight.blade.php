<div class="flex flex-col gap-2">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Quick Actions --}}
        <x-layouts.employee.cards title="Quick Actions">
            <div class="grid grid-cols-2 gap-4 lg:gap-2 h-full pb-6 lg:pb-9 md:pb-8">
                <x-layouts.employee.quick-action color="green" text="Absen">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-10 lg:size-16 md:size-14">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a7.464 7.464 0 0 1-1.15 3.993m1.989 3.559A11.209 11.209 0 0 0 8.25 10.5a3.75 3.75 0 1 1 7.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 0 1-3.6 9.75m6.633-4.596a18.666 18.666 0 0 1-2.485 5.33" />
                    </svg>
                </x-layouts.employee.quick-action>
                <x-layouts.employee.quick-action color="lime" text="Cuti">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-10 lg:size-16 md:size-14">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
                    </svg>
                </x-layouts.employee.quick-action>
                <x-layouts.employee.quick-action color="amber" text="Lembur">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-10 lg:size-16 md:size-14">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </x-layouts.employee.quick-action>

                <x-layouts.employee.quick-action color="amber-2" text="Izin">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-10 lg:size-16 md:size-14">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </x-layouts.employee.quick-action>
            </div>
        </x-layouts.employee.cards>
        {{-- Performance Card --}}

        <x-layouts.employee.cards title="Performance">
            <div class="grid h-full grid-cols-[1fr_auto] grid-rows-[auto_auto] gap-2 items-start">
                <div>
                    <div class="text-4xl sm:text-5xl lg:text-8xl mt-5 font-bold text-yellow-500">72%</div>
                    <div class="text-sm text-neutral-500">Efficiency Score</div>
                </div>
                <div class="text-yellow-500 flex-shrink-0 ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="h-12 w-12 sm:h-12 sm:w-12 lg:h-32 lg:w-32">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg>
                </div>
                <div class="avg col-span-2">
                    <div class="sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">7.2 hours</div>
                    <div class="text-xs text-neutral-500">Average Work Time</div>
                </div>
            </div>
        </x-layouts.employee.cards>
        {{-- Vacation Balance Card --}}
        <x-layouts.employee.cards title="Vacation Balance">
            <div class="flex items-center gap-2 sm:gap-3 mb-2 sm:mb-3 lg:mb-4">
                <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-400">18</div>
                <div class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">Days Remaining</div>
            </div>
            <div class="flex flex-wrap gap-0.5 sm:gap-1">
                @for ($i = 1; $i <= 18; $i++)
                    <div class="h-2 w-2 sm:h-3 sm:w-3 rounded-full bg-green-400"></div>
                @endfor
                @for ($i = 1; $i <= 12; $i++)
                    <div class="h-2 w-2 sm:h-3 sm:w-3 rounded-full bg-neutral-300 dark:bg-neutral-600"></div>
                @endfor
            </div>
        </x-layouts.employee.cards>

        {{-- Salary Card --}}
        <x-layouts.employee.cards title="Salary">
            <div class="mb-2 sm:mb-3 lg:mb-4">
                <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-orange-500">$4,500.00</div>
                <div class="text-xs sm:text-sm text-green-500">+ $750.00</div>
            </div>
            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
                <button
                    class="flex items-center justify-center gap-1 rounded-md bg-orange-500 px-2 py-1.5 sm:px-3 sm:py-2 text-xs font-medium text-white hover:bg-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3">
                        <path d="M7 14l5-5 5 5z" />
                    </svg>
                    <span class="hidden sm:inline">Raise</span>
                </button>
                <button
                    class="flex items-center justify-center gap-1 rounded-md border border-neutral-300 px-2 py-1.5 sm:px-3 sm:py-2 text-xs font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                    <span class="hidden sm:inline">History</span>
                </button>
            </div>
        </x-layouts.employee.cards>


    </div>
</div>
