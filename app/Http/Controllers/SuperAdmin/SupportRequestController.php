<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperAdmin\SupportRequest;

class SupportRequestController extends Controller
{
    public function index()
    {
        $requests = SupportRequest::with(['instansi', 'requester'])->latest()->paginate(15);
        return view('superadmin.support_requests.index', compact('requests'));
    }

    public function show($id)
    {
        $requestItem = SupportRequest::with(['instansi', 'requester'])->findOrFail($id);
        return view('superadmin.support_requests.show', compact('requestItem'));
    }

    public function update(Request $request, $id)
    {
        $requestItem = SupportRequest::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,normal,high,urgent',
            'admin_notes' => 'nullable|string',
        ]);
        $requestItem->update($validated);
        return redirect()->back()->with('success', 'Support request diperbarui.');
    }
} 