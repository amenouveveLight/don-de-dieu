<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{ 
    public function index(Request $request)
    {
        $query = User::query();
         

        // 🔍 Recherche par nom, prénom ou email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('firstname', 'like', "%{$request->search}%")
                  ->orWhere('lastname', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // 🎯 Filtre par rôle (admin / agent)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('updated_at', 'desc')->paginate(10);


        // ✏️ Edition utilisateur (si edit_id dans l'URL)
        $editUser = $request->filled('edit_id')
            ? User::find($request->edit_id)
            : null;

       

        return view('utilisateurs.index', compact('users', 'editUser'));
    }
    public function create()
    {
        return view('utilisateurs.create'); // Formulaire d'entrée
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6',
            'role'      => 'required|in:admin,agent,gerant',
        ]);

        User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'status'    => 1, // actif par défaut
        ]);

        return redirect()->route('utilisateurs')->with('success', 'Utilisateur ajouté.');
    }


   public function edit($id)
{
    $user = User::findOrFail($id);
    return view('utilisateurs.edit', compact('user'));
}



    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => "required|email|unique:users,email,$id",
            'role'      => 'required|in:admin,agent,gerant',

            
        ]);

        $user->update([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'role'      => $request->role,
           
        ]);

        
       return redirect()
    ->route('utilisateurs.edit', $user->id)->with('success', 'Utilisateur mis à jour.');
 
    }


    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('utilisateurs')->with('success', 'Utilisateur supprimé.');
    }
}
