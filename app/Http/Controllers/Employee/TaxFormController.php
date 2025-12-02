<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeTaxForm;
use App\Models\Admin\Employee;
use Illuminate\Http\Request;

class TaxFormController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return redirect()->route('employee.dashboard')->with('error', 'Data karyawan tidak ditemukan.');
        }

        $forms = EmployeeTaxForm::where('employee_id', $employee->id)
            ->where('is_published', true)
            ->orderBy('tax_year', 'desc')
            ->get();

        return view('employee.tax_forms.index', compact('forms'));
    }

    public function show(EmployeeTaxForm $taxForm)
    {
        // Ensure the form belongs to the logged-in employee
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->first();

        if ($taxForm->employee_id !== $employee->id || !$taxForm->is_published) {
            abort(403);
        }

        return view('admin.tax_forms.print', compact('taxForm')); // Reuse the print view
    }
}
