<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Don;
use App\Models\User;
use Carbon\Carbon;

class DonController extends Controller
{
    /**
     * Afficher les dons de l'utilisateur connecté (confirmés uniquement).
     */
    public function index()
    {
        $dons = Don::where('user_id', Auth::id())->where('etat', 'confirmé')->get();
        return response()->json($dons);
    }

    /**
     * Enregistrement d’un nouveau don.
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
                'user_id' => $validated['user_id'],
                'etat' => $validated['etat'],
            ]);

            // Attribution de points
            $user = User::find($validated['user_id']);
            $user->points += 10;
            $user->save();

            return response()->json([
                'message' => 'Don créé avec succès.',
                'don' => $don,
                'points' => $user->points
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création du don.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Voir les dons confirmés de l'utilisateur connecté.
     */
    public function myDon()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
        }

        $dons = Don::where('user_id', $user->id)
                    ->where('etat', 'confirmé')
                    ->with('annonce.user')
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

    /**
     * Confirmer un don via API.
     */
    public function confirmDon($id)
    {
        $don = Don::find($id);
        if ($don && $don->etat !== 'confirmé') {
            $don->etat = 'confirmé';
            $don->save();

            // Ajouter des points
            $user = $don->user;
            $user->points += 10;
            $user->save();
        }

        return response()->json($don);
    }

    /**
     * Annuler un don via API.
     */
    public function annulerDon($id)
    {
        $don = Don::findOrFail($id);
        $don->etat = 'annulé';
        $don->save();

        return response()->json($don);
    }

    /**
     * ✅ [Admin] Liste de tous les dons dans la vue Blade.
     */
    public function listeAdmin()
    {
        $dons = Don::with(['user', 'annonce'])->latest()->get();
        return view('dons.index', compact('dons'));
    }

    /**
     * ✅ [Admin] Confirmer un don et attribuer des points.
     */
    public function confirmerViaWeb($id)
    {
        $don = Don::findOrFail($id);
        if ($don->etat !== 'confirmé') {
            $don->etat = 'confirmé';
            $don->save();

            $user = $don->user;
            $user->points += 10;
            $user->save();
        }

        return redirect()->back()->with('success', 'Don confirmé avec succès.');
    }

    /**
     * ✅ [Admin] Annuler un don.
     */
    public function annulerViaWeb($id)
    {
        $don = Don::findOrFail($id);
        $don->etat = 'annulé';
        $don->save();

        return redirect()->back()->with('success', 'Don annulé avec succès.');
    }
}
