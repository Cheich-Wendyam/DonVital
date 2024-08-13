<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BloodRequestNotification;
use App\Events\AnnonceCreee;
use App\Models\Notification;

class AnnonceController extends Controller
{
    /**
     * Affiche la liste des annonces.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    // Récupère les annonces avec les informations de l'utilisateur associé, triées par date de création décroissante
    $annonces = Annonce::with('user')->orderBy('created_at', 'desc')->get();

    return response()->json($annonces);
}


    /**
     * Affiche le formulaire de création d'une annonce.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Retourner une vue ou un formulaire si nécessaire
    }

    /**
     * Stocke une nouvelle annonce dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
   
     public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'titre' => ['required', 'string', 'max:255'],
             'description' => ['required', 'string'],
             'raison' => ['nullable', 'string'],
             'TypeSang' => ['nullable', 'string'],
             'CentreSante' => ['nullable', 'string'],
         ]);
     
         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }
     
         $user = Auth::user(); // Obtenir l'utilisateur actuellement connecté
     
         if (!$user) {
             return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
         }
     
         // Créer l'annonce
         $annonce = Annonce::create([
             'titre' => $request->titre,
             'description' => $request->description,
             'raison' => $request->raison,
             'TypeSang' => $request->TypeSang,
             'CentreSante' => $request->CentreSante,
             'user_id' => $user->id, // Associer l'annonce à l'utilisateur
         ]);
     
         // Inclure l'image de l'utilisateur dans la réponse
         $annonce->userImage = $user->image;
     
        
     
         // Récupérer les utilisateurs dont le groupe sanguin correspond
        $usersToNotify = User::where('blood_group', $request->TypeSang)->get();

        // Créer une notification pour chaque utilisateur correspondant
        foreach ($usersToNotify as $userToNotify) {
            Notification::create([
                'user_id' => $userToNotify->id,
                'annonce_id' => $annonce->id,
                'titre'=> 'Annonce de demande de sang',
                'message' => 'Une nouvelle annonce de demande de sang correspond à votre groupe sanguin!',
            ]);
        }
     
         return response()->json([
             'message' => 'Annonce publiée avec succès.',
             'annonce' => $annonce,
         ], 201);
     }
     

    /**
     * Affiche les détails d'une annonce spécifique.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $annonce = Annonce::find($id);

        if (!$annonce) {
            return response()->json(['message' => 'Annonce non trouvée.'], 404);
        }

        return response()->json($annonce);
    }

    /**
     * Affiche le formulaire d'édition d'une annonce.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Retourner une vue ou un formulaire si nécessaire
    }

    /**
     * Met à jour une annonce spécifique.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'raison' => ['nullable', 'string'],
            'TypeSang' => ['nullable', 'string'],
            'CentreSante' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $annonce = Annonce::find($id);

        if (!$annonce) {
            return response()->json(['message' => 'Annonce non trouvée.'], 404);
        }

        $annonce->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'raison' => $request->raison,
            'TypeSang' => $request->TypeSang,
            'CentreSante' => $request->CentreSante,
        ]);

        return response()->json([
            'message' => 'Annonce mise à jour avec succès.',
            'annonce' => $annonce,
        ]);
    }

    /**
     * Supprime une annonce spécifique.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $annonce = Annonce::find($id);

        if (!$annonce) {
            return response()->json(['message' => 'Annonce non trouvée.'], 404);
        }

        $annonce->delete();

        return response()->json(['message' => 'Annonce supprimée avec succès.']);
    }

    public function getNotifications()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
    }

    $notifications = Notification::where('user_id', $user->id)->where('read', false)->get();
    //recuperer les notifications par ordre decroissant 
    $notifications->sortByDesc('created_at');

    return response()->json($notifications);
}

}
