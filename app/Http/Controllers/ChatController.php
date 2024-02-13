<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }
    public function index()
    {
        try {
            $chats = Chat::all();
            return response()->json($chats, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not retrieve chats'], 500);
        }
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function show(Chat $chat)
{
    $user = $chat->user;

    return response()->json([
        'success' => true,
        'message' => $chat->message,
        'details' => $chat->details,
        'sent_by' => $user->name,
    ]);
}
   public function store(Request $request)
{
    $request->validate([
        'message' => 'required|string',
        'details' => 'nullable|string',
    ], [
        'message.required' => 'A message is required',
        'message.string' => 'The message must be a string',
    ]);

    $userId = auth()->user()->id;

    try {
        $chat = Chat::create([
            'user_id' => $userId,
            'message' => $request->message,
            'details' => $request->details,
        ]);

        // Dispatch an event here
        event(new NewChatMessage($chat));

        return response()->json([
            'success' => true,
            'message' => 'Chat created successfully',
            'chat' => $chat,
        ], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Could not create chat', 'exception' => $e->getMessage()], 500);
    }
}
}
