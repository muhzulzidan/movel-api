<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

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
            })->with(['messages', 'receiver', 'user.passenger', 'order.statusOrder', 'statusOrder'])->get();

            // Add the status_label and order_status to each chat
            $chats->each(function ($chat) {
                if ($chat->order && $chat->order->statusOrder) {
                    $chat->status_label = $chat->order->statusOrder->status_label;
                    $chat->order_status = $chat->order->statusOrder->id == 7 ? 'completed' : 'ongoing';
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
})->with(['messages', 'receiver', 'user.passenger', 'order.statusOrder', 'statusOrder'])->get();

// Add the status_label and order_status to each chat
$chats->each(function ($chat) {
    if ($chat->order && $chat->order->statusOrder) {
        $chat->status_label = $chat->order->statusOrder->status_label;
        $chat->order_status = $chat->order->statusOrder->id == 7 ? 'completed' : 'ongoing';
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

    public function destroy(Chat $chat)
    {
        // Create a Guzzle HTTP client
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://admin.movel.id', // Replace with the actual base URI of your Node.js server
        ]);

        // Delete a chat
        $response = $client->delete("/api/passenger/chat/{$chat->id}");

        $chat->delete();

        return response()->json([
            'status' => true,
            'message' => 'Chat deleted successfully',
        ], 200);
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
        $receiverId = $request->receiver_id;

        // If the authenticated user is a driver, swap the user_id and receiver_id
        if (auth()->user()->role_id == 3) { // Assuming 3 is the role_id for drivers
            $userId = $request->receiver_id;
            $receiverId = auth()->user()->id;
        }

        try {
            $chat = Chat::create([
                'user_id' => $userId,
                'receiver_id' => $receiverId,
                'details' => $request->details,
                'order_id' => $request->order_id,
            ]);

            // Fetch user details
            $passenger = User::find($userId);
            $driver = User::find($receiverId);

            // Create a message for the passenger
            Message::create([
                'chat_id' => $chat->id,
                'user_id' => $userId,
                'content' => "Chat created by passenger: {$passenger->name}", // Replace with actual passenger details
            ]);

            // Create a message for the driver
            Message::create([
                'chat_id' => $chat->id,
                'user_id' => $receiverId,
                'content' => "Chat received by driver: {$driver->name}", // Replace with actual driver details
            ]);

            // Create a Guzzle HTTP client
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://admin.movel.id', // Replace with the actual base URI of your Node.js server
            ]);

            // Create a chat
            $response = $client->post('/api/chats', [
                'json' => [
                    'passengerId' => $userId,
                    'driverId' => $receiverId,
                ],
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

                // Create a Guzzle HTTP client
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://admin.movel.id', // Replace with the actual base URI of your Node.js server
        ]);


        if ($chat) {
            $chat->order_id = $request->order_id; // The ID of the order associated with this chat
            $chat->details = $request->details; // The details of the chat
            $chat->save();
            

            // Update a chat
            $response = $client->put("/api/chat/{$chatId}", [
                'json' => [
                    'order_id' => $chat->order_id,
                    'details' => $chat->details,
                ],
            ]);

            return response()->json(['message' => 'Chat updated successfully', 'chat' => $chat], 200);
        }

        return response()->json(['message' => 'Chat not found'], 404);
    }
}
