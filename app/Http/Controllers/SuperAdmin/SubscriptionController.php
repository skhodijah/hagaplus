<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        // List all subscriptions
        return view('superadmin.subscriptions.index');
    }

    public function show($id)
    {
        return view('superadmin.subscriptions.show', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update subscription status
    }
}
