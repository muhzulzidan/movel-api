<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // Add this line
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UserLoggedIn implements ShouldBroadcast // Modify this line
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        Log::info('UserLoggedIn event instantiated');
        $this->user = $user;
        Log::info($user);
    }

    public function broadcastOn()
    {
        Log::info('Broadcasting UserLoggedIn event');
        return new PrivateChannel('user-logged-in');
    }
}