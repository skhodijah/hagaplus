<?php

namespace App\Models\Admin;

use App\Models\Payroll as BasePayroll;

/**
 * Backward compatibility shim for the old Admin\Payroll model.
 * The new Payroll model resides in App\Models\Payroll.
 * This class simply extends the new model so existing imports continue to work.
 */
class Payroll extends BasePayroll
{
    // No additional logic needed.
}
