<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification as BaseNotification;
use NotificationChannels\Fcm\Resources\Notification;
use Illuminate\Http\Request;

class BloodRequestNotification extends BaseNotification implements ShouldQueue
{
    protected $annonce;
    public function __construct($annonce)
    {
        $this->annonce = $annonce;
    }
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData(['key' => 'value'])
            ->setNotification(
                Notification::create()
                    ->setTitle('Besoin de Sang')
                    ->setBody('Une nouvelle demande de sang correspond à votre groupe sanguin!')
            );
    }
}
