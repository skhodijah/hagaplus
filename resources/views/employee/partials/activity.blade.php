<div class="flex flex-col gap-2">
    <div id="employee-calendar" class="mb-4">
        <div class="mb-3 flex items-center justify-between">
            <div id="cal-month-label" class="text-sm font-semibold"></div>
            <div class="flex items-center gap-1">
                <button id="cal-prev" type="button"
                    class="rounded-md border border-neutral-200 px-2 py-1 text-xs dark:border-neutral-700">‹</button>
                <button id="cal-next" type="button"
                    class="rounded-md border border-neutral-200 px-2 py-1 text-xs dark:border-neutral-700">›</button>
            </div>
        </div>
        <div class="mb-1 grid grid-cols-7 text-center text-[11px] text-neutral-500">
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
            <div>Sun</div>
        </div>
        <div id="cal-grid" class="grid grid-cols-7 gap-1"></div>
    </div>

    <!-- Activity Log Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-bold text-neutral-400">Log Activity</h4>
            <div class="flex items-center gap-2">
                <button type="button" class="text-xs text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                    Filter
                </button>
                <button type="button" class="text-xs text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                    Export
                </button>
            </div>
        </div>
        
        <ul class="mt-2 space-y-2 text-sm">
            {{-- Check-in On Time --}}
            <x-layouts.employee.log type="check-in" time="08:55" date="Mon, 09 Sep 2025" badge="on-time" status="On Time" dotColor="green" />

            {{-- Check-out On Time --}}
            <x-layouts.employee.log type="check-out" time="17:30" date="Mon, 09 Sep 2025" badge="on-time" status="On Time" dotColor="green" />

            {{-- Check-in Late --}}
            <x-layouts.employee.log type="check-in" time="09:15" date="Tue, 10 Sep 2025" badge="late" status="Late" penalty="Rp25,000" dotColor="red" />

            {{-- Break Time --}}
            <x-layouts.employee.log type="break" time="12:00" date="Tue, 10 Sep 2025" status="45 min" badge="overtime" />

            {{-- Check-out On Time --}}
            <x-layouts.employee.log type="check-out" time="17:00" date="Tue, 10 Sep 2025" badge="on-time" status="On Time" dotColor="green" />

            {{-- Overtime --}}
            <x-layouts.employee.log type="overtime" time="18:00" date="Tue, 10 Sep 2025" status="2 hours" penalty="Rp100,000" dotColor="orange" />
        </ul>

        <!-- View All Logs Button -->
        <div class="flex justify-center pt-4">
            <a href="#" class="inline-flex items-center gap-2 rounded-md border border-neutral-200 px-4 py-2 text-xs text-neutral-600 hover:bg-neutral-50 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                </svg>
                Lihat Semua Log
            </a>
        </div>
    </div>
</div>
