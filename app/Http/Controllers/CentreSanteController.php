<?php

namespace App\Http\Controllers;

use App\Models\CentreSante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CentreSanteController extends Controller
{

    public function create() {
        return view('centre_sante.create');
    }

    /**
     * Retourne la liste des centres de sante
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
    */
    public function index()
    {
        $centres = CentreSante::all();
        return response()->json($centres);
    }

    /**
     * Enregistre un nouveau centre de sante
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
    */
    public function CreateCentre(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'localisation' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
    
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }
    
        CentreSante::create($validated);
    
        return redirect()->back()->with('success', 'Centre de santé ajouté avec succès');
    }
    

    public function show($id)
    {
        $centre = CentreSante::findOrFail($id);
        return response()->json($centre);
    }

    public function update(Request $request, $id)
    {
        $centre = CentreSante::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'localisation' => 'string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($request->hasFile('image')) {
            // Supprimez l'ancienne image si nécessaire
            if ($centre->image) {
                Storage::disk('public')->delete($centre->image);
            }

            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }

        $centre->update($validated);
        return redirect()->route('centre_sante.index')->with('success', 'Centre de santé mis à jour avec succès');
    }

    public function destroy($id)
    {
        $centre = CentreSante::findOrFail($id);

        if ($centre->image) {
            Storage::disk('public')->delete($centre->image);
        }

        $centre->delete();
        return redirect()->back()->with('success', 'Centre de santé supprimé avec succès');
    }

    //recuperer les centre de santé 
    public function getCentreSante()
    {
        $centres = CentreSante::all();
        return view('centre_sante.index', compact('centres'));
    }
}
