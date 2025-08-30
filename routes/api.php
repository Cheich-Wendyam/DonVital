<?php


use Google\Client as GoogleClient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CentreSanteController;
use App\Http\Controllers\DonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PubController;
use App\Http\Controllers\SendNotification;
use App\Http\Controllers\CampagneController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DonationRecordController;
use App\Http\Controllers\CampagneParticipationController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EducationProgressController;

//use App\Http\Controllers\ConversationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/education/contents', [EducationController::class, 'getContents']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());


    // Routes de messagerie
    Route::post('/conversations', [ChatController::class, 'createConversation']);
    Route::get('/conversations', [ChatController::class, 'getUserConversations']);
    Route::post('/messages/send/{conversationId}', [ChatController::class, 'sendMessage']);
    Route::get('/messages/{conversationId}', [ChatController::class, 'getMessages']);
    // routes/api.php
    Route::get('/contacts', [UserController::class, 'getContacts']);
    Route::get('/users', [UserController::class, 'index1']);


    Route::get('/donation-records', [DonationRecordController::class, 'index']);
    Route::post('/donation-records', [DonationRecordController::class, 'store']);
    Route::delete('/donation-records/{id}', [DonationRecordController::class, 'destroy']);
    Route::get('/next-donation-date', [DonationRecordController::class, 'nextEligibleDate']);
    Route::get('/carnet/{userId}', [DonationRecordController::class, 'show'])->name('carnet.show');



    Route::get('/education/contents/{id}', [EducationController::class, 'getContentDetail']);
    Route::post('/education/contents/{id}/complete', [EducationController::class, 'completeContent']);
    Route::get('/education/progress', [EducationController::class, 'getUserProgress']);
    Route::get('/education/recommended', [EducationController::class, 'getRecommendedContents']);
    Route::post('/education/contents', [EducationController::class, 'apiStore']);
});

Route::get('/test-route', function () {
    return response()->json(['message' => 'Test route OK']);
});






//chatbot
Route::middleware('auth:sanctum')->post('/chatbot', [ChatbotController::class, 'ask']);



Route::post('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'apiRegister']);

Route::post('login', [App\Http\Controllers\Auth\RegisteredUserController::class, 'apiLogin']);
Route::post('logout', [ProfileController::class, 'deconnexion'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'getProfile']);
Route::middleware('auth:sanctum')->post('/updateprofile', [ProfileController::class, 'updateProfile']);
Route::post('/groupsanguin', [RegisteredUserController::class, 'BloodGroup'])
    ->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->get('/rewards', [RewardController::class, 'getRewards']);
Route::middleware('auth:sanctum')->post('/rewards/claim/{id}', [RewardController::class, 'claimReward']);

Route::get('/annonces', [AnnonceController::class, 'index']);
Route::middleware('auth:sanctum')->post('/annonces', [AnnonceController::class, 'store']);
Route::get('/annonces/{id}', [AnnonceController::class, 'show']);
Route::put('/annonces/{id}', [AnnonceController::class, 'update']);
Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);

Route::post('/fcm', [RegisteredUserController::class, 'updateFcmToken']);

Route::get('/notifications', [AnnonceController::class, 'getNotifications'])->middleware('auth:sanctum');
Route::post('/mark/{id}', [AnnonceController::class, 'markAsRead'])->middleware('auth:sanctum');
Route::get('/historiques', [AnnonceController::class, 'HistoriqueAnnonces'])->middleware('auth:sanctum');


Route::get('/notifications/{id}/annonce', [AnnonceController::class, 'getAnnonceByNotification']);

Route::apiResource('centres', CentreSanteController::class)->middleware('auth:sanctum');
Route::apiResource('dons',DonController::class)->middleware('auth:sanctum');


Route::get('/annonce/{id}/dons', [AnnonceController::class, 'getDons'])->middleware('auth:sanctum');
Route::post('annonceEtat/{id}', [AnnonceController::class, 'desactiverAnnonce'])->middleware('auth:sanctum');
Route::get('myDon', [DonController::class, 'myDon'])->middleware('auth:sanctum');
Route::post('confirmDon/{id}', [DonController::class, 'confirmDon'])->middleware('auth:sanctum');
Route::post('annulerDon/{id}', [DonController::class, 'annulerDon'])->middleware('auth:sanctum');
Route::apiResource('pub',PubController::class)->middleware('auth:sanctum');



// ✅ Route publique
Route::get('/campagnes/actives', [CampagneController::class, 'activeCampagnes'])->name('campagnes.actives');

// ✅ Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/campagnes/{id}', [CampagneController::class, 'show'])->where('id', '[0-9]+');
    Route::post('/campagnes', [CampagneController::class, 'store']);
    Route::put('/campagnes/{id}', [CampagneController::class, 'update']);
    Route::delete('/campagnes/{id}', [CampagneController::class, 'destroy']);

    Route::get('/user/campagnes', [CampagneParticipationController::class, 'userCampagnes']);
    Route::post('/campagnes/{id}/register', [CampagneParticipationController::class, 'register']);
    Route::delete('/campagnes/{id}/unregister', [CampagneParticipationController::class, 'unregister']);
    Route::get('/campagnes/{id}/participants', [CampagneParticipationController::class, 'campagneUsers']);

});





Route::post('/passwordlink', [PasswordResetLinkController::class, 'sendResetLinkEmail']);
Route::post('/passwordreset', [PasswordResetLinkController::class, 'updatePassword']);
Route::post('/verify', [PasswordResetLinkController::class, 'verifyCode'] );

Route::get('/send', [SendNotification::class, 'sendNotification']);



Route::get('/sendnotifications', function () {
    // Récupérer les tokens FCM des utilisateurs concernés
    $users = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

    // Si vous n'avez pas d'utilisateurs avec des tokens, retournez une erreur
    if (empty($users)) {
        return response()->json([
            'message' => 'Aucun utilisateur avec un token FCM'
        ], 404);
    }

    $title = "Bonjour";
    $description = "Ceci est une notification pour plusieurs utilisateurs.";

    // Charger le fichier d'authentification Google
    $credentialsFilePath = public_path('json/file.json'); // Assurez-vous que ce chemin est correct
    if (!file_exists($credentialsFilePath)) {
        return response()->json([
            'message' => 'Le fichier d\'authentification Google est introuvable.'
        ], 500);
    }

    $client = new GoogleClient();
    $client->setAuthConfig($credentialsFilePath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $client->refreshTokenWithAssertion();

    // Vérifiez si le token d'accès est bien récupéré
    $token = $client->getAccessToken();
    if (isset($token['access_token'])) {
        $access_token = $token['access_token'];
    } else {
        return response()->json([
            'message' => 'Erreur lors de la récupération du token d\'accès.'
        ], 500);
    }

    $headers = [
        "Authorization: Bearer $access_token",
        'Content-Type: application/json'
    ];

    $fcm = $users;
    $data = [
        "message" => [
            "token" => $fcm,
            "notification" => [
                "title" => $title,
                "body" => $description,
            ],
        ]
    ];

    $payload = json_encode($data);

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/donvital/messages:send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Exécution de la requête
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    // Gestion des erreurs cURL
    if ($err) {
        return response()->json([
            'message' => 'Erreur cURL: ' . $err
        ], 500);
    } else {
        return response()->json([
            'message' => 'Notifications envoyées à plusieurs utilisateurs',
            'response' => json_decode($response, true)
        ]);
    }
})->name('sendnotifications');
