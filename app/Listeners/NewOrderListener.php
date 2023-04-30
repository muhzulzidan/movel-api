<?php

namespace App\Listeners;

use App\Events\NewOrderNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class NewOrderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewOrderNotification $event)
    {
        $order = $event->order;
        $driver_id = $order->user_id;

        Notification::send(User::find($driver_id), new NewOrderNotification($order));
    }
}
