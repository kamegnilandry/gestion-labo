<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $utilisateurs = User::orderBy('name')->get();

        return view('users.index', compact('utilisateurs'));
    }

    public function create(): View
    {
        return view('users.create', ['roles' => User::roles()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, true);
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $utilisateur): View
    {
        return view('users.edit', ['user' => $utilisateur, 'roles' => User::roles()]);
    }

    public function update(Request $request, User $utilisateur): RedirectResponse
    {
        $data = $this->validateData($request, false, $utilisateur);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $utilisateur->update($data);

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(Request $request, User $utilisateur): RedirectResponse
    {
        if ($utilisateur->id === $request->user()->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $utilisateur->update(['actif' => false]);

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur désactivé.');
    }

    private function validateData(Request $request, bool $creation, ?User $user = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.($user?->id)],
            'role' => ['required', 'in:'.implode(',', array_keys(User::roles()))],
            'telephone' => ['nullable', 'string', 'max:30'],
            'actif' => ['sometimes', 'boolean'],
        ];

        $rules['password'] = $creation
            ? ['required', 'string', 'min:8']
            : ['nullable', 'string', 'min:8'];

        $data = $request->validate($rules, [], [
            'name' => 'nom',
            'email' => 'e-mail',
            'role' => 'rôle',
            'telephone' => 'téléphone',
            'password' => 'mot de passe',
        ]);

        $data['actif'] = $request->boolean('actif', true);

        return $data;
    }
}
