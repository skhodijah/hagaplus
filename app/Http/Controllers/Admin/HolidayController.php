<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = \App\Models\Admin\Holiday::orderBy('start_date', 'desc')->paginate(10);
        return view('admin.holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.holidays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_national_holiday' => 'boolean',
        ]);

        \App\Models\Admin\Holiday::create($request->all());

        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $holiday = \App\Models\Admin\Holiday::findOrFail($id);
        return view('admin.holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $holiday = \App\Models\Admin\Holiday::findOrFail($id);
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_national_holiday' => 'boolean',
        ]);

        $holiday->update($request->all());

        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $holiday = \App\Models\Admin\Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday deleted successfully.');
    }
}
