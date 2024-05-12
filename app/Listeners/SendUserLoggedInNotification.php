<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class SendUserLoggedInNotification
{
    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event
     * @return void
     */
    public function handle(UserLoggedIn $event)
    {
        $data = [
            'event' => 'App\\Events\\UserLoggedIn',
            'data' => [
                'user' => $event->user
            ],
            'socket' => ''
        ];

        Redis::publish('socket.io', json_encode($data));
    }
}