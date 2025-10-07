<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Models\SuperAdmin\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with(['instansi']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by instansi
        if ($request->filled('instansi_id')) {
            $query->where('instansi_id', $request->instansi_id);
        }


        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get filter options
        $instansis = Instansi::select('id', 'nama_instansi')->get();
        $roles = ['superadmin', 'admin', 'employee'];

        return view('superadmin.users.index', compact('users', 'instansis', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $instansis = Instansi::where('is_active', true)->get();

        return view('superadmin.users.create', compact('instansis'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:superadmin,admin,employee',
            'instansi_id' => 'required_if:role,admin,employee|exists:instansis,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'role', 'instansi_id']);
        $data['password'] = Hash::make($request->password);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user = User::create($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['instansi', 'employees.branch']);

        // Get user's activity summary
        $totalLogins = 0; // Would need login logs table
        $lastLogin = null; // Would need login logs table

        return view('superadmin.users.show', compact('user', 'totalLogins', 'lastLogin'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $instansis = Instansi::where('is_active', true)->get();

        return view('superadmin.users.edit', compact('user', 'instansis'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:superadmin,admin,employee',
            'instansi_id' => 'required_if:role,admin,employee|exists:instansis,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $data = $request->only(['name', 'email', 'phone', 'role', 'instansi_id']);

        // Prevent changing role of superadmin users
        if ($user->role === 'superadmin' && $data['role'] !== 'superadmin') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['role' => 'Superadmin role cannot be changed.']);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        // Prevent superadmin from deactivating themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account.');
        }

        // Prevent deactivating other superadmins
        if ($user->role === 'superadmin' && auth()->user()->role !== 'superadmin') {
            return redirect()->back()
                ->with('error', 'You do not have permission to modify superadmin accounts.');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "User {$user->name} has been {$status}.");
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deletion of superadmin users
        if ($user->role === 'superadmin') {
            return redirect()->back()
                ->with('error', 'Cannot delete superadmin users.');
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        // Delete avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Bulk actions for users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        $count = 0;

        foreach ($users as $user) {
            // Skip superadmin users for delete action
            if ($user->role === 'superadmin') {
                continue;
            }

            // Prevent self-deletion
            if ($user->id === auth()->id()) {
                continue;
            }

            switch ($request->action) {
                case 'delete':
                    // Delete avatar if exists
                    if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                        Storage::disk('public')->delete($user->avatar);
                    }
                    $user->delete();
                    $count++;
                    break;
            }
        }

        return redirect()->back()
            ->with('success', "{$count} users have been deleted.");
    }
}