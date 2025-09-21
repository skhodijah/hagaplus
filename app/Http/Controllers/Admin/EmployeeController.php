<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee.index');
    }
}