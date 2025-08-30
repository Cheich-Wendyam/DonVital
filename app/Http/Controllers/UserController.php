<?php

// app/Http/Controllers/UserController.php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('user', ['users' => $users, 'roles' => $roles]);
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Compte utilisateur ajouté avec succès.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $role = Role::findById($request->role);
        $user->syncRoles([$role->name]);

        return redirect()->back()->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function changeRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::findOrFail($request->role);
        $user->syncRoles([$role->name]);

        return redirect()->back()->with('success', 'Le rôle a été mis à jour avec succès.');
    }
    public function getContacts() {
    $users = User::select(
            'id',
            'name',
            'telephone',
            'image',
            'blood_group as bloodGroup' // Alias pour correspondre au frontend
        )
        ->where('id', '!=', auth()->id())
        ->get();

    return response()->json($users);
}
    public function index1()
    {
        return User::all(); // ou un format paginé
    }


}
