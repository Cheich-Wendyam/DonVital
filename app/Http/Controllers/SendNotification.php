<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

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
}
