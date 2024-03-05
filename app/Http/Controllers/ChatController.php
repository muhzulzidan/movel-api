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
        $this->middleware('auth:sanctum');
        // $this->middleware('checkRole:3,2')->only(['index', 'show']); // assuming 2 and 3 are the roles for passengers and drivers
    }

    public function index(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Determine the user's role and fetch the relevant chats
        try {
            if ($user->role->id == 2 || $user->role->id == 3) { // assuming 2 and 3 are the roles for passengers and drivers

                $chats = Chat::where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
                })->with(['messages', 'receiver', 'user', 'order.statusOrder', 'statusOrder'])->get();
            } else {
                // If the user is neither a passenger nor a driver, return an empty array
                $chats = [];
            }
            return response()->json(['user' => $user, 'chats' => $chats], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Chat $chat)
    {
        // Eager load the messages relationship
        $chat->load('messages');

        // Return the chat as a JSON response
        return response()->json($chat);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|integer',
        ], [
            'receiver_id.required' => 'A receiver ID is required',
            'receiver_id.integer' => 'The receiver ID must be an integer',
        ]);

        $userId = auth()->user()->id;

        try {
            $chat = Chat::create([
                'user_id' => $userId,
                'receiver_id' => $request->receiver_id,
            ]);

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
