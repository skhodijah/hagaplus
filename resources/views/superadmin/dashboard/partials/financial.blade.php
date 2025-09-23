<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Pending Payments</h3>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $pendingPayments }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Revenue Forecast</h3>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $revenueForecastFormatted }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Outstanding Invoices</h3>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $outstandingInvoicesAmountFormatted }}</p>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Payment Method Breakdown</h3>
        <div class="h-40 rounded-md bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Integrasi chart nanti</div>
    </div>
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Refund Requests</h3>
        <div class="h-40 rounded-md bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">Integrasi chart nanti</div>
    </div>
</div> 