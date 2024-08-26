<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    // recuperer les utilisateurs pour les afficher dans la vue user.blade.php
    public function index () {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('user', ['users' => $users, 'roles' => $roles]);
    }

    public function role()
    {
        $roles = Role::all(); // Récupère tous les rôles
        return view('role', compact('roles')); 
    }

    public function changeRole(Request $request, $id)
{
    $user = User::findOrFail($id);
    $role = Role::findOrFail($request->role);

    // Supprimer les rôles actuels de l'utilisateur et assigner le nouveau rôle
    $user->syncRoles([$role->name]);

    return redirect()->back()->with('success', 'Le rôle a été mis à jour avec succès.');
}

public function CreateRole(Request $request)
    {
        // Validation du formulaire
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        // Création du rôle avec Spatie
        Role::create(['name' => $request->name]);

        // Redirection avec un message de succès
        return redirect()->back()->with('success', 'Rôle ajouté avec succès.');
    }

    // créer un compte utilisateur
    public function CreateUser(Request $request)
    {
        // Validation du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Création de l'utilisateur
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirection avec un message de sélection
        return redirect()->back()->with('success', 'Compte utilisateur ajouté avec succès.');
    }

    public function deleteUser($id)
{
    // Trouver l'utilisateur par ID
    $user = User::findOrFail($id);

    // Supprimer l'utilisateur
    $user->delete();

    // Redirection avec un message de succès
    return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
}

public function updateUser(Request $request, $id) {
    $user = User::findOrFail($id);

    // Validation du formulaire
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
    ]);

    // Mise à jour de l'utilisateur
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);
    // Find the role by its ID
    $role = Role::findById($request->role);

    // assigner le role selectionner dans le formulaire
    $user->syncRoles([$role->name]);
    

    // Redirection avec un message de sélection
    return redirect()->back()->with('success', 'Utilisateur mis à jour avec succès.');
}

   
    
}