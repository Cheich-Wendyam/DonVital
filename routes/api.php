<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'apiRegister']);

Route::post('login', [App\Http\Controllers\Auth\RegisteredUserController::class, 'apiLogin']);
Route::post('logout', [ProfileController::class, 'deconnexion'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'getProfile']);
Route::middleware('auth:sanctum')->post('/updateprofile', [ProfileController::class, 'updateProfile']);
Route::post('/groupsanguin', [RegisteredUserController::class, 'BloodGroup'])
    ->middleware('auth:sanctum');

Route::get('/annonces', [AnnonceController::class, 'index']);
Route::middleware('auth:sanctum')->post('/annonces', [AnnonceController::class, 'store']);
Route::get('/annonces/{id}', [AnnonceController::class, 'show'])->middleware('auth:sanctum');
//Route::put('/annonces/{id}', [AnnonceController::class, 'update']);
//Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);

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

Route::post('/passwordlink', [PasswordResetLinkController::class, 'sendResetLinkEmail']);
Route::post('/passwordreset', [PasswordResetLinkController::class, 'updatePassword']);
Route::post('/verify', [PasswordResetLinkController::class, 'verifyCode'] );

Route::get('/send', [SendNotification::class, 'sendNotification']);

use Google\Client as GoogleClient;

use App\Models\User;

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
