<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'lang' => 'sometimes|string|max:10'
        ]);

        $message = strip_tags($request->input('message'));
        $languageCode = $request->input('lang', 'fr-FR');

        $sessionId = $request->user()
            ? 'user_'.$request->user()->id
            : 'guest_'.$request->ip();

        $cacheKey = md5('chatbot:'.$sessionId.':'.$message.':'.$languageCode);

        // ✅ Utiliser un cache simple (pas de tags)
        if (Cache::has($cacheKey)) {
            return response()->json([
                'response' => Cache::get($cacheKey),
                'cached' => true
            ]);
        }

        try {
            $credentialsPath = storage_path(
                env('DIALOGFLOW_CREDENTIALS_PATH', 'dialogflow-credentials.json')
            );

            if (!file_exists($credentialsPath)) {
                throw new \Exception("Credentials file missing");
            }

            $sessionsClient = new SessionsClient([
                'credentials' => $credentialsPath
            ]);

            $projectId = json_decode(file_get_contents($credentialsPath), true)['project_id'];
            $session = $sessionsClient->sessionName($projectId, $sessionId);

            $textInput = (new TextInput())
                ->setText($message)
                ->setLanguageCode($languageCode);

            $queryInput = (new QueryInput())->setText($textInput);

            $response = $sessionsClient->detectIntent($session, $queryInput);
            $fulfillmentText = $response->getQueryResult()->getFulfillmentText();

            $sessionsClient->close();

            // ✅ Cache simple compatible `file`
            Cache::put($cacheKey, $fulfillmentText, now()->addHours(1));

            return response()->json([
                'response' => $fulfillmentText,
                'session' => $sessionId
            ]);

        } catch (\Exception $e) {
            Log::error("Dialogflow error: " . $e->getMessage());

            $errorMessage = config('app.debug')
                ? "Dialogflow error: ".$e->getMessage()
                : "Désolé, le service est temporairement indisponible";

            return response()->json([
                'response' => $errorMessage,
                'error' => true
            ], 500);
        }
    }
}
