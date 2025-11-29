<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminManagementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = User::where('instansi_id', $user->instansi_id)
            ->where('system_role_id', 2); // 2 is Admin

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $admins = $query->latest()->paginate(10);

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'system_role_id' => 2, // Admin role
                'instansi_id' => auth()->user()->instansi_id,
                'email_verified_at' => now(),
            ]);

            return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating admin: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambahkan admin: ' . $e->getMessage());
        }
    }

    public function edit(User $admin)
    {
        // Ensure admin belongs to same instansi
        if ($admin->instansi_id !== auth()->user()->instansi_id) {
            abort(403);
        }
        
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if ($admin->instansi_id !== auth()->user()->instansi_id) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$admin->id],
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $admin->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.admins.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        if ($admin->instansi_id !== auth()->user()->instansi_id) {
            abort(403);
        }

        if ($admin->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}
