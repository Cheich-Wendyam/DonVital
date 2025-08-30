<?php



namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class AdminConversationController extends Controller
{
    /**
     * Affiche toutes les conversations dans l'interface admin.
     */
    public function index()
    {
        $conversations = Conversation::with(['userOne', 'userTwo'])
            ->orderBy('created_at', 'desc')
            ->get();

        $users = User::orderBy('name')->get();

        // Ajoutez $users dans compact()
        return view('admin.conversations.index', compact('conversations', 'users'));
    }
    /**
     * Affiche les messages d’une conversation spécifique.
     */
    public function show($id)
    {
        $conversation = Conversation::with(['userOne', 'userTwo'])->findOrFail($id);
        $messages = Message::where('conversation_id', $id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.conversations.messages', compact('conversation', 'messages'));
    }

    /**
     * Formulaire pour créer une conversation manuellement.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.conversations.create', compact('users'));
    }


    /**
     * Enregistre une nouvelle conversation entre deux utilisateurs.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_one_id' => 'required|exists:users,id|different:user_two_id',
            'user_two_id' => 'required|exists:users,id',
        ]);

        $exists = Conversation::where(function ($query) use ($request) {
            $query->where('user_one_id', $request->user_one_id)
                  ->where('user_two_id', $request->user_two_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('user_one_id', $request->user_two_id)
                  ->where('user_two_id', $request->user_one_id);
        })->first();

        if ($exists) {
            return redirect()->back()->with('error', 'Une conversation entre ces deux utilisateurs existe déjà.');
        }

        Conversation::create([
            'user_one_id' => $request->user_one_id,
            'user_two_id' => $request->user_two_id,
        ]);

        return redirect()->route('admin.conversations.index')->with('success', 'Conversation créée avec succès.');
    }
}
