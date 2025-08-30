<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampagneParticipation;
use App\Models\Campagne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
class CampagneParticipationController extends Controller
{
    public function register($campagneId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Non authentifié'], 401);
            }

            $campagne = Campagne::find($campagneId);

            if (!$campagne) {
                return response()->json(['message' => 'Campagne non trouvée'], 404);
            }

            // Vérifier si déjà inscrit
            if (CampagneParticipation::where('user_id', $user->id)
                ->where('campagne_id', $campagneId)
                ->exists()) {
                return response()->json(['message' => 'Déjà inscrit à cette campagne'], 409);
            }

            // Créer la participation
            $participation = CampagneParticipation::create([
                'user_id' => $user->id,
                'campagne_id' => $campagneId,
                'status' => 'registered'
            ]);

            Log::info("Utilisateur {$user->id} inscrit à campagne {$campagneId}");

            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie',
                'data' => $participation
            ], 201);

        } catch (\Exception $e) {
            Log::error("Erreur inscription campagne: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function unregister($campagneId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Non authentifié'], 401);
            }

            $participation = CampagneParticipation::where('user_id', $user->id)
                ->where('campagne_id', $campagneId)
                ->first();

            if (!$participation) {
                return response()->json(['message' => 'Participation non trouvée'], 404);
            }

            $participation->delete();

            Log::info("Utilisateur {$user->id} désinscrit de campagne {$campagneId}");

            return response()->json([
                'success' => true,
                'message' => 'Désinscription réussie'
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur désinscription campagne: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function userCampagnes()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Non authentifié'], 401);
            }

            $participations = CampagneParticipation::with(['campagne' => function($query) {
                    $query->select('id', 'titre', 'date_debut', 'date_fin', 'lieu');
                }])
                ->where('user_id', $user->id)
                ->get()
                ->map(function($participation) {
                    return $participation->campagne;
                });

            return response()->json([
                'success' => true,
                'data' => $participations
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur récupération campagnes utilisateur: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function campagneUsers($campagneId)
    {
        try {
            $campagne = Campagne::find($campagneId);

            if (!$campagne) {
                return response()->json(['message' => 'Campagne non trouvée'], 404);
            }

            // Récupérer les utilisateurs inscrits
            $participants = CampagneParticipation::with(['user' => function($query) {
                    $query->select('id', 'name', 'email', 'phone'); // ajoute les colonnes que tu veux exposer
                }])
                ->where('campagne_id', $campagneId)
                ->get()
                ->map(function($participation) {
                    return $participation->user;
                });

            return response()->json([
                'success' => true,
                'data' => $participants
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur récupération utilisateurs campagne: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function showParticipants($campagneId)
    {
        $campagne = Campagne::with(['participants.user'])->find($campagneId);

        if (!$campagne) {
            return redirect()->back()->with('error', 'Campagne non trouvée');
        }

        return view('campagnes.participants', compact('campagne'));
    }
    public function allParticipants()
{
    $campagnes = \App\Models\Campagne::with(['participants.user'])->get();

    return view('campagnes.all_participants', compact('campagnes'));
}



}
