<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all(); 
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Validation du formulaire
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $data = $request->all();

        // Mise à jour du rôle avec Spatie
        $role->update($data);
        return redirect()->route('roles.index')->with('success', 'Rôle mise à jour avec succès');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rôle supprimé avec succès');
        
    }

     /**
     * Show the form for assigning permissions to the specified role.
     */
    public function showAssignPermissionsForm(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.index', compact('role', 'permissions'));
    }

    /**
     * Assign the selected permissions to the specified role.
     */
    public function assignPermissions(Request $request, Role $role)
    {
        // Récupérer les IDs des permissions
        $permissionIds = $request->input('permissions', []);
    
        // Trouver les permissions par leurs IDs
        $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $permissionIds)->pluck('name');
    
        // Assigner les permissions au rôle
        $role->syncPermissions($permissions);
    
        return redirect()->route('roles.index')->with('success', 'Permissions mises à jour avec succès.');
    }
    
}
