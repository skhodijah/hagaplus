<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        // List all attendance records
        return view('admin.attendance.index');
    }

    public function create()
    {
        return view('admin.attendance.create');
    }

    public function store(Request $request)
    {
        // Store attendance record
    }

    public function show($id)
    {
        return view('admin.attendance.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.attendance.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update attendance record
    }

    public function destroy($id)
    {
        // Delete attendance record
    }
}
