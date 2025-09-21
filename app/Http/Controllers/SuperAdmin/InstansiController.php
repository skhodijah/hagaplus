<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\Package;
use App\Models\SuperAdmin\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all non-deleted instansi with pagination
        $instansis = Instansi::latest()->paginate(10);
        return view('superadmin.instansi.index', compact('instansis'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packages = Package::where('is_active', true)->get();
        return view('superadmin.instansi.create', compact('packages'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'subdomain' => 'required|string|max:50|unique:instansis,subdomain',
            'email' => 'required|email|max:255|unique:instansis,email',
            'phone' => 'required|string|max:20',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'negara' => 'required|string|max:100',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,suspended',
            'status_langganan' => 'required|in:trial,active,expired,canceled',
            'package_id' => 'required|exists:packages,id',
            'tanggal_mulai_langganan' => 'required|date',
            'tanggal_akhir_langganan' => 'required|date|after:tanggal_mulai_langganan',
            'catatan' => 'nullable|string',
        ]);
        
        // Handle file upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/instansi/logos');
            $validated['logo'] = Storage::url($path);
        }
        
        // Create new instansi
        $instansi = Instansi::create($validated);
        
        // Create subscription record
        $instansi->subscriptions()->create([
            'package_id' => $validated['package_id'],
            'start_date' => $validated['tanggal_mulai_langganan'],
            'end_date' => $validated['tanggal_akhir_langganan'],
            'status' => $validated['status_langganan'],
            'price' => Package::find($validated['package_id'])->price,
        ]);
        
        return redirect()->route('superadmin.instansi.index')
            ->with('success', 'Instansi berhasil ditambahkan.');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Instansi $instansi)
    {
        $instansi->load(['subscriptions.package', 'users', 'karyawans']);
        return view('superadmin.instansi.show', compact('instansi'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instansi $instansi)
    {
        $packages = Package::where('is_active', true)->get();
        $instansi->load('subscriptions');
        return view('superadmin.instansi.edit', compact('instansi', 'packages'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instansi $instansi)
    {
        $validated = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'subdomain' => [
                'required',
                'string',
                'max:50',
                Rule::unique('instansis', 'subdomain')->ignore($instansi->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('instansis', 'email')->ignore($instansi->id),
            ],
            'phone' => 'required|string|max:20',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'negara' => 'required|string|max:100',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,suspended',
            'status_langganan' => 'required|in:trial,active,expired,canceled',
            'package_id' => 'required|exists:packages,id',
            'tanggal_mulai_langganan' => 'required|date',
            'tanggal_akhir_langganan' => 'required|date|after:tanggal_mulai_langganan',
            'catatan' => 'nullable|string',
        ]);
        
        // Handle file upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($instansi->logo) {
                $oldLogo = str_replace('/storage', 'public', $instansi->logo);
                Storage::delete($oldLogo);
            }
            
            $path = $request->file('logo')->store('public/instansi/logos');
            $validated['logo'] = Storage::url($path);
        }
        
        // Update instansi
        $instansi->update($validated);
        
        // Update or create subscription
        $instansi->subscriptions()->updateOrCreate(
            ['instansi_id' => $instansi->id],
            [
                'package_id' => $validated['package_id'],
                'start_date' => $validated['tanggal_mulai_langganan'],
                'end_date' => $validated['tanggal_akhir_langganan'],
                'status' => $validated['status_langganan'],
                'price' => Package::find($validated['package_id'])->price,
            ]
        );
        
        return redirect()->route('superadmin.instansi.index')
            ->with('success', 'Data instansi berhasil diperbarui.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instansi $instansi)
    {
        // Soft delete the instansi
        $instansi->delete();
        
        return redirect()->route('superadmin.instansi.index')
            ->with('success', 'Instansi berhasil dihapus (masuk ke arsip).');
    }
    
    /**
     * Display a listing of the trashed resources.
     */
    public function trash()
    {
        $instansis = Instansi::onlyTrashed()->latest()->paginate(10);
        return view('superadmin.instansi.trash', compact('instansis'));
    }
    
    /**
     * Restore the specified resource from trash.
     */
    public function restore($id)
    {
        $instansi = Instansi::onlyTrashed()->findOrFail($id);
        $instansi->restore();
        
        return redirect()->route('superadmin.instansi.trash')
            ->with('success', 'Instansi berhasil dipulihkan.');
    }
    
    /**
     * Permanently delete the specified resource.
     */
    public function forceDelete($id)
    {
        $instansi = Instansi::onlyTrashed()->findOrFail($id);
        
        // Delete logo if exists
        if ($instansi->logo) {
            $logo = str_replace('/storage', 'public', $instansi->logo);
            Storage::delete($logo);
        }
        
        // Permanently delete
        $instansi->forceDelete();
        
        return redirect()->route('superadmin.instansi.trash')
            ->with('success', 'Instansi berhasil dihapus permanen.');
    }
}
