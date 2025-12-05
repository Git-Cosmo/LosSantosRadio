<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with(['roles', 'socialAccounts']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->get('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $role));
        }

        $users = $query->latest()->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function edit(User $user): View
    {
        $roles = Role::all();

        return view('admin.users.edit', [
            'user' => $user->load(['roles', 'socialAccounts']),
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'roles' => 'array',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['roles' => $validated['roles'] ?? []])
            ->log('updated user');

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['name' => $user->name, 'email' => $user->email])
            ->log('deleted user');

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
