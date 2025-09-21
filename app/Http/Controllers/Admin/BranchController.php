<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        // List all branches
        return view('admin.branches.index');
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        // Store new branch
    }

    public function show($id)
    {
        return view('admin.branches.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.branches.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update branch
    }

    public function destroy($id)
    {
        // Delete branch
    }
}
