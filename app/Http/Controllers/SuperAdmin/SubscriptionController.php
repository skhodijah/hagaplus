<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperAdmin\Subscription;

class SubscriptionController extends Controller
{
    /**
     * Tampilkan daftar semua subscription
     */
    public function index()
    {
        // Ambil data subscription dengan relasi instansi & package
        $subscriptions = Subscription::with(['instansi', 'package'])->paginate(10);

        return view('superadmin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Tampilkan detail subscription tertentu
     */
    public function show($id)
    {
        $subscription = Subscription::with(['instansi', 'package'])->findOrFail($id);

        return view('superadmin.subscriptions.show', compact('subscription'));
    }

    /**
     * Update status subscription (misalnya active/expired)
     */
    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $subscription->status = $request->input('status', $subscription->status);
        $subscription->save();

        return redirect()->route('superadmin.subscriptions.index')
                         ->with('success', 'Subscription berhasil diperbarui.');
    }
}
