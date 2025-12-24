<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $admins = User::where('role', 'admin')->orderBy('name')->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        return view('admin.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'locale' => 'fr',
            'currency' => 'FCFA',
        ]);

        return redirect()->route('admin.admins.index')->with('status', 'Compte administrateur créé.');
    }

    public function edit(User $admin): View
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $admin->name = $data['name'];

        if (! empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('status', 'Compte administrateur mis à jour.');
    }

    public function destroy(User $admin): RedirectResponse
    {
        if (auth()->id() === $admin->id) {
            return back()->with('status', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('status', 'Compte administrateur supprimé.');
    }
}