<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    // 🔁 Liste des conversations de l'utilisateur
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->with(['users:id,name,email', 'lastMessage'])
            ->latest('updated_at')
            ->get();

        return response()->json($conversations);
    }

    // ✅ Création d'une conversation
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array|min:2',
            'user_ids.*' => 'exists:users,id',
            'name' => 'nullable|string|max:255',
            'is_group' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $userIds = $request->user_ids;
        $isGroup = $request->boolean('is_group', false);
        $name = $request->input('name');

        // 🔍 Vérifie s’il existe déjà une conversation privée entre ces deux utilisateurs
        if (!$isGroup && count($userIds) === 2) {
            $existing = Conversation::where('is_group', false)
                ->whereHas('users', function ($q) use ($userIds) {
                    $q->whereIn('user_id', $userIds);
                }, '=', count($userIds))
                ->get();

            foreach ($existing as $conv) {
                if ($conv->users->pluck('id')->sort()->values()->all() == collect($userIds)->sort()->values()->all()) {
                    return response()->json($conv);
                }
            }
        }

        // 💬 Création de la nouvelle conversation
        $conversation = Conversation::create([
            'name' => $isGroup ? $name : null,
            'is_group' => $isGroup,
        ]);

        // 👥 Associer les utilisateurs
        $conversation->users()->attach($userIds);

        return response()->json($conversation->load('users'));
    }
     public function index1()
    {
        $conversations = Conversation::with('participants')->get();
        return view('admin.conversations.index', compact('conversations'));
    }

    public function store1(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'user_ids' => 'required|array|min:2',
        ]);

        $conversation = Conversation::create([
            'name' => $request->name,
            'is_group' => true,
        ]);

        $conversation->participants()->attach($request->user_ids);

        return redirect()->back()->with('success', 'Conversation créée avec succès.');
    }

    public function destroy(Conversation $conversation)
    {
        $conversation->delete();
        return redirect()->back()->with('success', 'Conversation supprimée.');
    }
}
