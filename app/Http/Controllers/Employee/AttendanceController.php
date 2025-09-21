<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        // Employee's own attendance records
        return view('employee.attendance.index');
    }

    public function checkIn()
    {
        // Check in functionality
    }

    public function checkOut()
    {
        // Check out functionality
    }
}
