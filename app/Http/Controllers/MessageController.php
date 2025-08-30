<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with(['user', 'conversation'])->latest()->get();
        return view('admin.messages.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'type' => 'required|in:text,image,file,audio',
        ]);

        Message::create($request->all());

        return redirect()->back()->with('success', 'Message ajouté.');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->back()->with('success', 'Message supprimé.');
    }
}
