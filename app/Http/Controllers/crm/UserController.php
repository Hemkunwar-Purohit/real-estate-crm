<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
{
    if (!auth()->user()->hasRole('admin')) {
        abort(403, 'Only admins can manage users.');
    }

    $users = User::with('roles')
        ->latest()
        ->paginate(config('crm.per_page'));

    // Har user ke leads aur deals count
    $users->each(function ($user) {
        $user->leads_count = \App\Models\Lead::where('assigned_to', $user->id)->count();
        $user->deals_count = \App\Models\Deal::where('assigned_to', $user->id)->count();
    });

    return view('crm.users.index', compact('users'));
}

    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $roles = Role::all();
        return view('crm.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()
            ->route('crm.users.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $roles = Role::all();
        return view('crm.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return redirect()
            ->route('crm.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        // Apna account delete nahi kar sakta
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()
            ->route('crm.users.index')
            ->with('success', 'User deleted!');
    }

    public function toggleStatus(User $user)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account!');
        }

        // active column nahi hai — role remove/add se kaam chalate hain
        // Ya simply delete — abhi skip karte hain
        return back()->with('info', 'Feature coming soon!');
    }
}