<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Services\FirebaseService;

class ChatController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService = null)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Démarrer ou récupérer une conversation privée
     */
    public function getOrCreatePrivateConversation(Request $request, $recipientId)
    {
        $recipient = User::findOrFail($recipientId);
        $user = $request->user();

        // Vérifier si une conversation existe déjà entre ces deux utilisateurs
        $conversation = Conversation::where('is_group', false)
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('participants', function ($query) use ($recipientId) {
                $query->where('user_id', $recipientId);
            })
            ->with('participants')
            ->first();

        if (!$conversation) {
            // Créer une nouvelle conversation privée
            $conversation = Conversation::create([
                'is_group' => false,
                'name' => null,
            ]);

            // Ajouter les participants
            $conversation->participants()->attach([$user->id, $recipientId]);
        }

        return response()->json([
            'conversation' => $conversation->load('participants')
        ]);
    }

    /**
     * Créer une conversation de groupe
     */
    public function createGroupConversation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'participants' => 'required|array|min:2',
            'participants.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $participants = $request->participants;

        // Ajouter l'utilisateur courant aux participants
        if (!in_array($user->id, $participants)) {
            $participants[] = $user->id;
        }

        $conversation = Conversation::create([
            'is_group' => true,
            'name' => $request->name,
        ]);

        $conversation->participants()->attach($participants);

        return response()->json([
            'conversation' => $conversation->load('participants')
        ], 201);
    }

    /**
     * Récupérer les conversations de l'utilisateur
     */
    public function getUserConversations(Request $request)
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->with(['participants', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return response()->json($conversations);
    }

    /**
     * Envoyer un message dans une conversation
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string',
            'type' => 'required|in:text,image,audio,file',
            'file' => 'nullable|file|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $conversation = Conversation::findOrFail($conversationId);
        $user = $request->user();

        // Vérifier que l'utilisateur fait partie de la conversation
        if (!$conversation->participants->contains($user->id)) {
            return response()->json(['error' => 'Accès non autorisé à cette conversation'], 403);
        }

        // Gestion des fichiers
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('messages', 'public');
        }

        // Créer le message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'content' => $request->input('content'),
            'type' => $request->type,
            'file_path' => $filePath,
            'is_read' => false,
        ]);

        // Mettre à jour la date du dernier message
        $conversation->update(['last_message_at' => now()]);

        // Envoyer des notifications aux autres participants
        $this->sendNotifications($message, $conversation, $user);

        return response()->json([
            'message' => $message->load('sender')
        ], 201);
    }

    /**
     * Récupérer les messages d'une conversation
     */
    public function getMessages($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $user = Auth::user();

        // Vérifier que l'utilisateur fait partie de la conversation
        if (!$conversation->participants->contains($user->id)) {
            return response()->json(['error' => 'Accès non autorisé à cette conversation'], 403);
        }

        $messages = Message::where('conversation_id', $conversation->id)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Marquer les messages comme lus
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    /**
     * Marquer un message comme lu
     */
    public function markMessageAsRead($messageId)
    {
        $message = Message::findOrFail($messageId);
        $user = Auth::user();

        // Vérifier que l'utilisateur fait partie de la conversation
        $conversation = $message->conversation;
        if (!$conversation->participants->contains($user->id)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        // Seuls les messages des autres peuvent être marqués comme lus
        if ($message->sender_id !== $user->id) {
            $message->update(['is_read' => true]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Supprimer une conversation pour l'utilisateur courant
     */
    public function leaveConversation($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $user = Auth::user();

        // Vérifier que l'utilisateur fait partie de la conversation
        if (!$conversation->participants->contains($user->id)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        // Retirer l'utilisateur de la conversation
        $conversation->participants()->detach($user->id);

        // Supprimer la conversation si elle devient vide
        if ($conversation->participants()->count() === 0) {
            $conversation->delete();
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Envoyer des notifications aux participants
     */
    private function sendNotifications(Message $message, Conversation $conversation, User $sender)
    {
        if (!$this->firebaseService) return;

        $recipients = $conversation->participants()
            ->where('user_id', '!=', $sender->id)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        if (empty($recipients)) return;

        // Construire le contenu de la notification
        $title = $conversation->is_group
            ? "{$sender->name} dans {$conversation->name}"
            : $sender->name;

        $content = $message->type === 'text'
            ? $message->content
            : "Vous avez reçu un fichier";

        // Envoyer la notification
        $this->firebaseService->sendNotifications(
            $recipients,
            $title,
            $content,
            [
                'conversation_id' => $conversation->id,
                'message_id' => $message->id,
                'type' => 'new_message'
            ]
        );
    }

    /**
     * Télécharger un fichier attaché à un message
     */
    public function downloadAttachment($messageId)
    {
        $message = Message::findOrFail($messageId);
        $user = Auth::user();

        // Vérifier que l'utilisateur fait partie de la conversation
        $conversation = $message->conversation;
        if (!$conversation->participants->contains($user->id)) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que le message a un fichier
        if (!$message->file_path || !Storage::disk('public')->exists($message->file_path)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::disk('public')->download($message->file_path);
    }
}
