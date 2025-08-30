<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $serviceAccountPath = Storage::path('/firebase-admin.json');
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $this->messaging = $factory->createMessaging();
    }

    /**
     * Envoie une notification à un seul appareil
     */
    public function sendNotification(string $token, string $title, string $body, array $data = []): void
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData(array_merge($data, [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ]));

        try {
            $this->messaging->send($message);
        } catch (\Throwable $th) {
            Log::error("Échec d'envoi à $token: " . $th->getMessage());
        }
    }

    /**
     * Envoie une notification à plusieurs appareils (optimisé)
     *
     * @param array $tokens Max 500 tokens par batch
     * @param string $title
     * @param string $body
     * @param array $data
     *
     * @return array Tokens invalides à supprimer
     */
    public function sendBulkNotifications(array $tokens, string $title, string $body, array $data = []): array
    {
        if (empty($tokens)) {
            return [];
        }

        $invalidTokens = [];
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData(array_merge($data, [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ]));

        try {
            $report = $this->messaging->sendMulticast($message, $tokens);
            $invalidTokens = $this->processReport($report);
        } catch (\Throwable $th) {
            Log::error("Échec d'envoi groupé: " . $th->getMessage());
        }

        return $invalidTokens;
    }

    /**
     * Traite le rapport d'envoi et identifie les tokens invalides
     */
    protected function processReport(MulticastSendReport $report): array
    {
        $invalidTokens = [];

        foreach ($report->getItems() as $item) {
            if (!$item->success() && $item->error()->getCode() === 'invalid-argument') {
                $invalidTokens[] = $item->target()->value();
            }
        }

        return $invalidTokens;
    }

    /**
     * Envoi asynchrone via queue
     */
    public function queueBulkNotifications(array $tokens, string $title, string $body, array $data = []): void
    {
        // Découpage en lots de 500 (limite FCM)
        foreach (array_chunk($tokens, 500) as $chunk) {
            dispatch(function () use ($chunk, $title, $body, $data) {
                $invalidTokens = $this->sendBulkNotifications($chunk, $title, $body, $data);

                // Nettoyer les tokens invalides
                if (!empty($invalidTokens)) {
                    $this->cleanInvalidTokens($invalidTokens);
                }
            });
        }
    }

    /**
     * Nettoie les tokens invalides en base de données
     */
    protected function cleanInvalidTokens(array $tokens): void
    {
        try {
            User::whereIn('fcm_token', $tokens)->update(['fcm_token' => null]);
        } catch (\Throwable $th) {
            Log::error("Nettoyage tokens échoué: " . $th->getMessage());
        }
    }
}
