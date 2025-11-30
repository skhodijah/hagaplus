<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('created_at', 'desc')->get();

        return view('superadmin.system.settings.payment-methods', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.system.settings.payment-methods-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank_transfer,qris,ewallet',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'qris_data' => 'nullable|string'
        ]);

        $data = [
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'is_active' => $request->has('is_active'),
        ];

        // Handle fields based on payment type
        if ($request->input('type') === 'bank_transfer') {
            $data['account_name'] = $request->input('bank_account_name');
            $data['account_number'] = $request->input('bank_account_number');
            $data['bank_name'] = $request->input('bank_name');
        } elseif ($request->input('type') === 'ewallet') {
            $data['account_name'] = $request->input('ewallet_account_name');
            $data['account_number'] = $request->input('ewallet_account_number');
        } elseif ($request->input('type') === 'qris') {
            $data['qris_data'] = $request->input('qris_data');
            
            // Handle QRIS image upload
            if ($request->hasFile('qris_image')) {
                $file = $request->file('qris_image');
                $filename = 'qris_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('qris', $filename, 'public');
                $data['qris_image'] = $path;
            }
        }

        PaymentMethod::create($data);

        return redirect()->route('superadmin.payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return view('superadmin.system.settings.payment-methods-show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return view('superadmin.system.settings.payment-methods-edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank_transfer,qris,ewallet',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'qris_data' => 'nullable|string'
        ]);

        $data = [
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'is_active' => $request->has('is_active'),
        ];

        // Handle fields based on payment type
        if ($request->input('type') === 'bank_transfer') {
            $data['account_name'] = $request->input('bank_account_name');
            $data['account_number'] = $request->input('bank_account_number');
            $data['bank_name'] = $request->input('bank_name');
            // Clear QRIS related fields when switching to bank transfer
            $data['qris_image'] = null;
            $data['qris_data'] = null;
        } elseif ($request->input('type') === 'ewallet') {
            $data['account_name'] = $request->input('ewallet_account_name');
            $data['account_number'] = $request->input('ewallet_account_number');
            $data['bank_name'] = null; // Clear bank name when switching to e-wallet
            $data['qris_image'] = null;
            $data['qris_data'] = null;
        } elseif ($request->input('type') === 'qris') {
            $data['account_name'] = null;
            $data['account_number'] = null;
            $data['bank_name'] = null;
            $data['qris_data'] = $request->input('qris_data');
            
            // Handle QRIS image upload
            if ($request->hasFile('qris_image')) {
                // Delete old image if exists
                if ($paymentMethod->qris_image && Storage::disk('public')->exists($paymentMethod->qris_image)) {
                    Storage::disk('public')->delete($paymentMethod->qris_image);
                }

                $file = $request->file('qris_image');
                $filename = 'qris_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('qris', $filename, 'public');
                $data['qris_image'] = $path;
            }
        }

        $paymentMethod->update($data);

        return redirect()->route('superadmin.payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        // Delete QRIS image if exists
        if ($paymentMethod->qris_image && Storage::disk('public')->exists($paymentMethod->qris_image)) {
            Storage::disk('public')->delete($paymentMethod->qris_image);
        }

        $paymentMethod->delete();

        return redirect()->route('superadmin.payment-methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }

    /**
     * Toggle active status of the payment method.
     */
    public function toggleStatus(string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);

        $status = $paymentMethod->is_active ? 'activated' : 'deactivated';

        return redirect()->route('superadmin.payment-methods.index')
            ->with('success', "Payment method {$status} successfully.");
    }
}
