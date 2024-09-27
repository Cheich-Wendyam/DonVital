<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Google\Client as GoogleClient;

use App\Models\User;

class SendNotification extends Controller
{
    public function SendNotification() {
        $firebase_credential = (new Factory)->withServiceAccount(base_path('donvital-firebase-adminsdk-66pdz-6147fe3b01.json'));

        $messaging = $firebase_credential->createMessaging();
        $message = CloudMessage::fromArray([
            'notification' => [
                'title' => 'Don Vital',
                'body' => 'Un nouveau don est disponible',
            ],

            'topic'=> 'global',

        ]);
        $messaging->send($message);

        return response()->json(['message' => 'Notification envoyée']);
    }


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

public function send_Notification($fcmToken, $title, $message) {
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

    $data = [
        "registration_ids" => [$fcmToken],
        "notification" => [
            "title" => $title,
            "body" => $message,
            "content_available" => true,
            "priority" => "high",
        ],
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
}

}
