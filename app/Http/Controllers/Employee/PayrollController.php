<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        // Employee's own payroll records
        return view('employee.payroll.index');
    }

    public function show($id)
    {
        // Show specific payroll record
        return view('employee.payroll.show', compact('id'));
    }
}
