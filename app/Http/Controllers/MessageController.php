<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class MessageController extends Controller
{
    public function index(Chat $chat)
    {
        // Fetch all messages for the given chat
        $messages = $chat->messages()->with(['user.driver', 'user.passenger'])->get();

        // Add isDriver, isPassenger and profile picture to each message
        $messages->transform(function ($message) {
            $message->isDriver = $message->user->driver != null;
            $message->isPassenger = $message->user->passenger != null;
            $message->profilePicture = $message->isDriver ? $message->user->driver->photo : ($message->isPassenger ? $message->user->passenger->photo : null);
            return $message;
        });
        
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

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://admin.movel.id', // Replace with the actual base URI of your Node.js server
        ]);

       // Send a message
        $response = $client->post("/api/passenger/chats/{$chat->id}/messages", [
            'json' => [
                'senderId' => Auth::id(),
                'content' => $request->content,
            ],
        ]);

        return response()->json($message, 201);

    }
}