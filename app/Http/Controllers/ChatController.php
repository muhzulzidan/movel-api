<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        // $this->middleware('checkRole:3,2')->only(['index', 'show']); // assuming 2 and 3 are the roles for passengers and drivers
    }

   public function indexView(Request $request)
{
    // Get the authenticated user
    $user = $request->user();

    // Determine the user's role and fetch the relevant chats
    try {
        if ($user->role->id == 2) { // assuming 2 is the role for passengers

            $chats = Chat::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })->with(['messages', 'receiver', 'user', 'order.statusOrder', 'statusOrder'])->get();

            // Add the status_label to each chat
            $chats->each(function ($chat) {
                if ($chat->order && $chat->order->statusOrder) {
                    $chat->status_label = $chat->order->statusOrder->status_label;
                }
            });

            // If chats is empty, return a view with a message
           if ($chats->isEmpty()) {
                return response()->json(['status' => 'empty', 'message' => 'No chats available'], 200);
            }
        } else {
            // If the user is neither a passenger nor a driver, return an empty array
            $chats = [];
        }
        return response()->json(['user' => $user, 'chats' => $chats], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
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

                // Add the status_label to each chat
                $chats->each(function ($chat) {
                    if ($chat->order && $chat->order->statusOrder) {
                        $chat->status_label = $chat->order->statusOrder->status_label;
                    }
                });
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
            'details' => 'required|string',
            'order_id' => 'required|integer',
        ], [
            'receiver_id.required' => 'A receiver ID is required',
            'receiver_id.integer' => 'The receiver ID must be an integer',
            'details.required' => 'Details are required',
            'details.string' => 'Details must be a string',
            'order_id.required' => 'An order ID is required',
            'order_id.integer' => 'The order ID must be an integer',
        ]);

        $userId = auth()->user()->id;

    try {
        $chat = Chat::create([
            'user_id' => $userId,
            'receiver_id' => $request->receiver_id,
            'details' => $request->details,
            'order_id' => $request->order_id,
        ]);

        Log::info('Chat created successfully', ['chat' => $chat]);

            return response()->json([
                'success' => true,
                'message' => 'Chat created successfully',
                'chat' => $chat,
            ], 201);
       } catch (\Exception $e) {
            Log::error('Could not create chat', ['exception' => $e]);

            return response()->json(['error' => 'Could not create chat', 'exception' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $chatId)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'details' => 'required|string',
        ], [
            'order_id.required' => 'An order ID is required',
            'order_id.integer' => 'The order ID must be an integer',
            'details.required' => 'Details are required',
            'details.string' => 'Details must be a string',
        ]);

        $chat = Chat::find($chatId);
        if ($chat) {
            $chat->order_id = $request->order_id; // The ID of the order associated with this chat
            $chat->details = $request->details; // The details of the chat
            $chat->save();

            return response()->json(['message' => 'Chat updated successfully', 'chat' => $chat], 200);
        }

        return response()->json(['message' => 'Chat not found'], 404);
    }
}
