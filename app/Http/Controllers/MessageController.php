<?php

namespace App\Http\Controllers;

use App\Models\Models\Chat;
use App\Models\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function store(Chat $chat, Request $request)
    {
        // Authorization...
        $this->authorize('view', $chat);

        // Validation...
        $data = $request->validate([
            'message' => 'required|string',
        ]);

        try {
            // Message creation...
            $message = $chat->messages()->create([
                'user_id' => $request->user()->id,
                'message' => $data['message'],
            ]);

            // Event broadcasting...
            broadcast(new MessageSent($message))->toOthers();

            return response()->json($message, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not create message'], 500);
        }
    }
}
