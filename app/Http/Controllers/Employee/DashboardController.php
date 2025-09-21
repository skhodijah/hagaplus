<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Employee dashboard logic
        return view('employee.dashboard.index');
    }
}
