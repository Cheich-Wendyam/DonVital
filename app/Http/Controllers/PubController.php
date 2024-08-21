<?php

namespace App\Http\Controllers;

use App\Models\Publicite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publicites = Publicite::all();
        return response()->json($publicites);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pub.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
        'libelle' => 'required|string|max:255',
        'image' => 'required|image|max:2048',
        'contenu' => 'required|string',
       ]);

       if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $validated['image'] = $path;
       }

       Publicite::create($validated);
       return redirect()->route('pub.create')->with('success', 'Publicité ajoutée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // afficher la publicité dont l'id est $id
        $pub = Publicite::findOrFail($id);
        return response()->json($pub);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


}
