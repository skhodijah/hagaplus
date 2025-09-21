<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Show settings page
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        // Update settings
    }
}
