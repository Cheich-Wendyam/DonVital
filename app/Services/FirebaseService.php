<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseService
{
    protected $messaging;
    public function __construct()
    {
        $serviceAccountPath = Storage::path('donvital-firebase-adminsdk-66pdz-1d442f8a84.json');
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($token, $title, $body, $data = []){

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification([
                'title' => $title,
                'body' => $body,
                'click_action'=> 'FLUTTER_NOTIFICATION_CLICK'
            ])
            ->withData($data);

            // Log::info($data);
            try {
                $this->messaging->send($message);

            } catch (\Throwable $th) {
                // throw $th;
                Log::alert("message not sent : ".$th->getMessage());
            }

    }

}
