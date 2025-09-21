<x-layouts.employee.layout :title="__('Karyawan')">
    <div class="employee-page  lg:grid lg:grid-cols-4 gap-2">
        {{-- profile --}}
        <div class="col-span-1 mb-2 rounded-xl bg-white p-0 overflow-hidden dark:border-neutral-800 dark:bg-neutral-800">
            @include('employee.partials.profile')
        </div>

         {{-- employee insight --}}
         <div class="col-span-2 mb-2 rounded-xl bg-white p-6 dark:border-neutral-800 dark:bg-neutral-800">
            @include('employee.partials.insight')
         </div>

        {{-- Activity --}}
        <div class="col-span-1 mb-2 rounded-xl bg-white p-6 dark:border-neutral-800 dark:bg-neutral-800">
            @include('employee.partials.activity')
        </div>
    </div>
</x-layouts.employee.layout>