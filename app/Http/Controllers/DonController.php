<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Don;
use Carbon\Carbon;

class DonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // afficher les dons de l'utilisateur connecté dont l'attribut 'etat'='confirmé'
        $dons = Don::where('user_id', Auth::id())->where('etat', 'confirmé')->get();
        return response()->json($dons);
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
        $validated = $request->validate([
            'annonce_id' => 'required|exists:annonces,id',
            'user_id' => 'required|exists:users,id',
            'etat' => 'required|in:en attente,confirmé,annulé',
        ]);
    
        try {
            $don = Don::create([
                'annonce_id' => $validated['annonce_id'],
                'user_id' => $validated['user_id'], // L'utilisateur connecté devient le donneur
                'etat' => $validated['etat'],
            ]);
    
            return response()->json([
                'message' => 'Don créé avec succès.',
                'don' => $don
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création du don.',
                'error' => $e->getMessage()
            ], 500);
        }
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

    // Afficher les dons de l'utilisateur connecté dont l'attribut 'etat'='confirmé'
    public function myDon()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
        }
    
        // Récupérer les dons avec les informations de l'annonce et de l'utilisateur ayant créé l'annonce
        $dons = Don::where('user_id', $user->id)
                    ->where('etat', 'confirmé')
                    ->with('annonce.user') // Charger l'annonce et l'utilisateur associé
                    ->get()
                    ->map(function ($don) {
                        return [
                            'id' => $don->id,
                            'annonce' => $don->annonce,
                            'user' => $don->user,
                            'created_at'=> $don->created_at->format('d-m-Y H:i'),
                            'etat' => $don->etat
                        ];
                    });
    
        return response()->json($dons);
    
    }

    // corfirmer le don
    public function confirmDon($id)
    {
        $don = Don::find($id);
        $don->etat = 'confirmé';
        $don->update();
        return response()->json($don);
    }

    //annulé la confirmation de don
    public function annulerDon($id){
        $don= Don::findOrFail($id);
        $don->etat= 'annulé';
        $don->update();
        return response()->json($don);
    }
}
