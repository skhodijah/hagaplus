document.addEventListener('DOMContentLoaded', function() {
    // Calculate salary summary
    function calculateSalarySummary() {
        const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
        const overtimeAmount = parseFloat(document.getElementById('overtime_amount').value) || 0;

        // Calculate allowances
        const allowanceAmounts = document.querySelectorAll('.allowance-amount');
        let totalAllowances = 0;
        allowanceAmounts.forEach(function(input) {
            totalAllowances += parseFloat(input.value) || 0;
        });

        // Calculate deductions
        const deductionAmounts = document.querySelectorAll('.deduction-amount');
        let totalDeductions = 0;
        deductionAmounts.forEach(function(input) {
            totalDeductions += parseFloat(input.value) || 0;
        });

        // Calculate totals
        const totalGross = basicSalary + totalAllowances + overtimeAmount;
        const netSalary = totalGross - totalDeductions;

        // Update display
        document.getElementById('total-gross').textContent = 'Rp ' + formatNumber(totalGross);
        document.getElementById('total-deductions').textContent = 'Rp ' + formatNumber(totalDeductions);
        document.getElementById('net-salary').textContent = 'Rp ' + formatNumber(netSalary);

        const paymentStatus = document.getElementById('payment_status').value;
        document.getElementById('summary-status').textContent = paymentStatus.charAt(0).toUpperCase() + paymentStatus.slice(1);
    }

    // Format number with Indonesian format
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    // Add allowance
    document.getElementById('add-allowance')?.addEventListener('click', function() {
        const container = document.getElementById('allowances-container');
        const newItem = document.createElement('div');
        newItem.className = 'allowance-item grid grid-cols-2 gap-4 mb-2';
        newItem.innerHTML = `
            <input type="text" name="allowance_names[]" placeholder="Allowance name"
                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 dark:text-gray-400">Rp</span>
                </div>
                <input type="number" name="allowances[]" placeholder="Amount" min="0" step="0.01"
                       class="w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white allowance-amount">
            </div>
        `;
        container.appendChild(newItem);

        // Add event listeners to new inputs
        const newAmountInput = newItem.querySelector('.allowance-amount');
        newAmountInput.addEventListener('input', calculateSalarySummary);
    });

    // Add deduction
    document.getElementById('add-deduction')?.addEventListener('click', function() {
        const container = document.getElementById('deductions-container');
        const newItem = document.createElement('div');
        newItem.className = 'deduction-item grid grid-cols-2 gap-4 mb-2';
        newItem.innerHTML = `
            <input type="text" name="deduction_names[]" placeholder="Deduction name"
                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 dark:text-gray-400">Rp</span>
                </div>
                <input type="number" name="deductions[]" placeholder="Amount" min="0" step="0.01"
                       class="w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white deduction-amount">
            </div>
        `;
        container.appendChild(newItem);

        // Add event listeners to new inputs
        const newAmountInput = newItem.querySelector('.deduction-amount');
        newAmountInput.addEventListener('input', calculateSalarySummary);
    });

    // Add event listeners to existing inputs
    const basicSalaryInput = document.getElementById('basic_salary');
    const overtimeAmountInput = document.getElementById('overtime_amount');
    const paymentStatusSelect = document.getElementById('payment_status');

    if (basicSalaryInput) {
        basicSalaryInput.addEventListener('input', calculateSalarySummary);
    }

    if (overtimeAmountInput) {
        overtimeAmountInput.addEventListener('input', calculateSalarySummary);
    }

    if (paymentStatusSelect) {
        paymentStatusSelect.addEventListener('change', calculateSalarySummary);
    }

    // Add event listeners to existing allowance and deduction amounts
    document.querySelectorAll('.allowance-amount').forEach(function(input) {
        input.addEventListener('input', calculateSalarySummary);
    });

    document.querySelectorAll('.deduction-amount').forEach(function(input) {
        input.addEventListener('input', calculateSalarySummary);
    });

    // Calculate initial summary
    calculateSalarySummary();

    // Form validation
    const payrollForm = document.getElementById('payroll-form');
    if (payrollForm) {
        payrollForm.addEventListener('submit', function(e) {
            // Validate that allowances and deductions have both name and amount
            const allowanceNames = document.querySelectorAll('input[name="allowance_names[]"]');
            const allowances = document.querySelectorAll('input[name="allowances[]"]');
            const deductionNames = document.querySelectorAll('input[name="deduction_names[]"]');
            const deductions = document.querySelectorAll('input[name="deductions[]"]');

            let hasError = false;

            // Check allowances
            for (let i = 0; i < allowanceNames.length; i++) {
                const name = allowanceNames[i].value.trim();
                const amount = parseFloat(allowances[i].value) || 0;

                if ((name && !amount) || (!name && amount)) {
                    hasError = true;
                    break;
                }
            }

            // Check deductions
            for (let i = 0; i < deductionNames.length; i++) {
                const name = deductionNames[i].value.trim();
                const amount = parseFloat(deductions[i].value) || 0;

                if ((name && !amount) || (!name && amount)) {
                    hasError = true;
                    break;
                }
            }

            if (hasError) {
                e.preventDefault();
                alert('Please ensure all allowances and deductions have both name and amount, or leave both fields empty.');
                return false;
            }

            return true;
        });
    }
});