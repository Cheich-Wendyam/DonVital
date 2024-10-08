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
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseService;


class AnnonceController extends Controller
{
    /**
     * Affiche la liste des annonces.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    // Récupère les annonces avec les informations de l'utilisateur associé, triées par date de création décroissante et dont l'attribut 'etat'='actif'
    $annonces = Annonce::with('user')->where('etat', 'actif')->orderBy('created_at', 'desc')->get();


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
             'etat' => ['nullable', 'string'],



         ]);

         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }

         $user = Auth::user(); // Obtenir l'utilisateur actuellement connecté

         if (!$user) {
             return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
         }

         $etat='inactif';

         // Créer l'annonce
         $annonce = Annonce::create([
             'titre' => $request->titre,
             'description' => $request->description,
             'raison' => $request->raison,
             'TypeSang' => $request->TypeSang,
             'CentreSante' => $request->CentreSante,
             'etat' => $etat,
             'user_id' => $user->id, // Associer l'annonce à l'utilisateur

         ]);

         // Inclure l'image de l'utilisateur dans la réponse
         $annonce->userImage = $user->image;

         return response()->json([
             'message' => 'Annonce publiée avec succès.',
             'annonce' => $annonce,
         ], 201);
     }

     public function store2(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'titre' => ['required', 'string', 'max:255'],
             'description' => ['required', 'string'],
             'raison' => ['nullable', 'string'],
             'TypeSang' => ['nullable', 'string'],
             'CentreSante' => ['nullable', 'string'],
             'etat' => ['nullable', 'string'],



         ]);

         $user = Auth::user(); // Obtenir l'utilisateur actuellement connecté

         $etat='actif';

         // Créer l'annonce
         $annonce = Annonce::create([
             'titre' => $request->titre,
             'description' => $request->description,
             'raison' => $request->raison,
             'TypeSang' => $request->TypeSang,
             'CentreSante' => $request->CentreSante,
             'etat' => $etat,
             'user_id' => $user->id, // Associer l'annonce à l'utilisateur

         ]);

         // Inclure l'image de l'utilisateur dans la réponse
         $annonce->userImage = $user->image;

         return redirect()->route('annonce.index')->with('success', 'Annonce publiée avec succès.');
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

        // recuperer l'utilisateur lié à l'annonce
        $user = $annonce->user;


        // retourner l'annonce et l'utilisateur correspondant
        return response()->json(['annonce' => $annonce, 'user' => $user]);
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
    // Update an existing announcement
    public function update(Request $request, $id)
    {
        // Find the announcement or throw a 404 if not found
        $annonce = Annonce::findOrFail($id);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'raison' => ['nullable', 'string'],
            'TypeSang' => ['nullable', 'string'],
            'CentreSante' => ['nullable', 'string'],
        ]);

        // Check for validation errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the announcement
        $annonce->update($request->only(['titre', 'description', 'raison', 'TypeSang', 'CentreSante']));

        return redirect()->route('annonce.index')->with('success', 'Annonce mise à jour avec succès.');
    }

    //obtenir les annonces
    public function getAnnonces(){

        $annonces = Annonce::all();
        return view('annonce.index', compact('annonces'));
    }






    /**
     * Supprime une annonce spécifique.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Vérifier si l'utilisateur a la permission de suppression
        if (!auth()->user()->can('suppression')) {
            abort(403, 'Accès refusé, vous n’avez pas la permission de supprimer des annonces.');
        }
        $annonce = Annonce::find($id);
        $annonce->delete();

        return redirect()->back()->with('success', 'Annonce supprimée avec succès.');
    }

    public function getNotifications()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
    }

    $notifications = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
    //recuperer les notifications par ordre decroissant
    $notifications->sortByDesc('created_at');

    return response()->json($notifications);
}

// marquer notification comme lu
public function markAsRead()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
    }

    Notification::where('user_id', $user->id)->where('read', false)->update(['read' => true]);

    return response()->json(['message' => 'Notifications marquées comme lues.']);
}


/**
 * Retourne les détails de l'annonce liée à une notification, incluant les informations de l'utilisateur responsable.
 *
 * @param int $notificationId
 * @return \Illuminate\Http\Response
 */
public function getAnnonceByNotification($id)
{
    // Trouver la notification
    $notification = Notification::find($id);
    if (!$notification) {
        return response()->json(['message' => 'Notification non trouvée.'], 404);
    }

    // Trouver l'annonce liée à la notification
    $annonce = Annonce::find($notification->annonce_id);
    if (!$annonce) {
        return response()->json(['message' => 'Annonce non trouvée.'], 404);
    }

    // Trouver l'utilisateur responsable de l'annonce
    $user = $annonce->user; // Suppose que la relation `user` est définie dans le modèle Annonce

    // Retourner les détails de l'annonce avec les informations de l'utilisateur
    return response()->json([
        'annonce' => $annonce,
        'user' => $user,
    ]);
}



// methode pour obtenir la liste des annonces publiés par l'utilisateur connecté
public function HistoriqueAnnonces()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
    }

    $annonces = Annonce::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($annonce) {
            return [
                'id' => $annonce->id,
                'titre' => $annonce->titre,
                'description' => $annonce->description,
                'etat'=>$annonce->etat,
                'created_at' => $annonce->created_at->format('d/m/Y H:i'),
            ];
        });

    return response()->json($annonces);
}

    /**
     * Récupérer les dons associés à une annonce avec les informations de l'utilisateur.
     */
    public function getDons($id)
    {
        $annonce = Annonce::with('dons.user')->find($id);

        if (!$annonce) {
            return response()->json(['message' => 'Annonce non trouvée'], 404);
        }
        // formater la date de don pour l'affichage
        $dons = $annonce->dons->map(function ($don) {
            return [
                'id' => $don->id,
                'user' => $don->user,
                'created_at' => $don->created_at->format('d/m/Y H:i'),
                'etat' => $don->etat
            ];
        });



        return response()->json($dons);
    }

    //mettre à jour l'etat d'une annonce à fermée
    public function desactiverAnnonce($id)
    {
        $annonce = Annonce::find($id);
        if (!$annonce) {
            return response()->json(['message' => 'Annonce non trouvée.'], 404);
        }
        $annonce->update(['etat' => 'fermé']);
        return response()->json(['message' => 'Annonce desactivée avec succès.']);
    }

  // Activer une annonce
public function activerAnnonce($id)
{
    // Vérifier les permissions
    if (!Auth::user()->hasPermissionTo('Approuver annonce')) {
        abort(403, 'Action non autorisée.');
    }

    // Récupérer l'annonce par son identifiant
    $annonce = Annonce::find($id);

    // Vérifier si l'annonce est déjà active
    if ($annonce->etat !== 'inactif') {
        return redirect()->route('annonce.index')->with('info', 'L\'annonce est déjà active.');
    }



    try {
        // Mettre à jour l'état de l'annonce à 'actif'
        $annonce->update(['etat' => 'actif']);

        // Récupérer les utilisateurs dont le groupe sanguin correspond
        $usersToNotify = User::where('blood_group', $annonce->TypeSang)->get();

        // Créer une notification pour chaque utilisateur correspondant
        foreach ($usersToNotify as $userToNotify) {
            // Enregistrer la notification dans la base de données
            Notification::create([
                'user_id' => $userToNotify->id,
                'annonce_id' => $annonce->id,
                'titre' => 'Annonce de demande de sang',
                'message' => 'Une nouvelle annonce de demande de sang correspond à votre groupe sanguin!',
            ]);

            // Envoyer une notification push via FCM si le token est disponible
            if ($userToNotify->fcm_token) {
                // $this->sendPushNotification($userToNotify->fcm_token, 'Annonce de demande de sang', 'Une nouvelle annonce de demande de sang correspond à votre groupe sanguin!');
                $firebaseService = new FirebaseService();
                $firebaseService->sendNotification($userToNotify->fcm_token, 'Annonce de demande de sang', 'Une nouvelle annonce de demande de sang correspond à votre groupe sanguin!', []);

            } else {
                Log::warning("Aucun token FCM pour l'utilisateur ID: " . $userToNotify->id);
            }
        }



        return redirect()->route('annonce.attente')->with('success', 'Annonce approuvée avec succès.');

    } catch (\Exception $e) {

        Log::error("Erreur lors de l'activation de l'annonce: " . $e->getMessage());
        return redirect()->route('annonce.attente')->with('error', 'Erreur lors de l\'approbation de l\'annonce.');
    }
}


/**
 * Envoyer une notification push via FCM
 */
protected function sendPushNotification($fcmToken, $title, $message)
{
    $SERVER_API_KEY = env('FCM_SERVER_KEY');

    $data = [
        "to" => $fcmToken,
        "notification" => [
            "title" => $title,
            "body" => $message,
        ],
    ];

    $dataString = json_encode($data);

    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);
    $error = curl_error($ch); // Capture curl error if any

    curl_close($ch);

    // Log the FCM response for debugging
    \Log::info("FCM Response: " . $response);
    \Log::error("FCM Error: " . $error); // Log error if curl fails

    return json_decode($response);
}


    public function showAnnonce($id) {
        $annonce = Annonce::find($id);
        return view('annonce.show', compact('annonce'));

    }

    public function attente()
{
    // Récupérer les annonces avec l'état 'inactif'
    $annonces = Annonce::where('etat', 'inactif')->get();

    // Retourner la vue 'attente' avec les annonces
    return view('annonce.attente', compact('annonces'));
}

public function fermees()
{
    // Récupérer les annonces avec l'état 'fermer'
    $annonces = Annonce::where('etat', 'fermé')->get();

    // Retourner la vue 'fermees' avec les annonces
    return view('annonce.fermees', compact('annonces'));
}

public function dons($id)
{
    // Récupérer l'annonce par son ID
    $annonce = Annonce::findOrFail($id);

    // Récupérer les dons associés à cette annonce
    $dons = $annonce->dons()->with('user')->where('etat', 'confirmé')->get();

    // Retourner la vue 'dons-associes' avec les dons et l'annonce
    return view('annonce.dons', compact('dons', 'annonce'));
}

public function reject($id)
{
    $annonce = Annonce::find($id);
    $annonce->update(['etat' => 'fermé']);
    // notifier l'utilisateur de l'annnonce qu'elle a ete rejetée
    $user = $annonce->user;
     // Envoyer une notification push via FCM si le token est disponible
     if ($user->fcm_token) {
        // $this->sendPushNotification($userToNotify->fcm_token, 'Annonce de demande de sang', 'Une nouvelle annonce de demande de sang correspond à votre groupe sanguin!');
        $firebaseService = new FirebaseService();
        $firebaseService->sendNotification($user->fcm_token, 'Votre derniere annonce est rejetée', 'Créez une nouvelle annonce en respectant les conditions', []);

    } else {
        Log::warning("Aucun token FCM pour l'utilisateur ID: " . $user->id);
    }

    return redirect()->route('annonce.index')->with('success', 'Annonce rejetée avec succès.');
}

}
