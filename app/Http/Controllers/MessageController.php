<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Chat $chat)
    {
        // Fetch all messages for the given chat
        $messages = $chat->messages;

        // Return the messages as a JSON response
        return response()->json($messages);
    }

    public function store(Request $request, Chat $chat)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        // Create a new message linked to the given chat
        $message = new Message;
        $message->user_id = Auth::id(); // Get the ID of the authenticated user
        $message->chat_id = $chat->id; // Get the chat_id from the Chat instance
        $message->content = $request->content;
        $message->save();


        return response()->json($message, 201);

    }
}