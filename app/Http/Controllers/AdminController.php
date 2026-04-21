<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        // Empêche les non-admins d’accéder au contrôleur
        $this->middleware('admin');
    }

    /**
     * Liste des utilisateurs + recherche + filtre
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%$search%")
                  ->orWhere('lastname', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Mode édition
        $editUser = $request->filled('edit_id')
            ? User::findOrFail($request->edit_id)
            : null;

        return view('admin.users.index', compact('users', 'editUser'));
    }

    /**
     * Création utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6',
            'role'      => 'required|in:admin,agent',
        ]);

        User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'status'    => 1, // actif par défaut
        ]);

        return back()->with('success', 'Utilisateur ajouté avec succès.');
    }

    /**
     * Mise à jour utilisateur
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => "required|email|unique:users,email,$id",
            'role'      => 'required|in:admin,agent',
            'status'    => 'required|boolean',
            'password'  => 'nullable|min:6',
        ]);

        $data = $request->only('firstname', 'lastname', 'email', 'role', 'status');

        // Mise à jour du mot de passe seulement si fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Suppression d'un utilisateur
     */
    public function destroy($id)
    {
        // Empêche un admin de se supprimer lui-même
        if (auth()->id() == $id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        User::findOrFail($id)->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }
}
