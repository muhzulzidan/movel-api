<?php

namespace App\Events;

use App\Broadcasting\NewOrderChannel;
use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return new NewOrderChannel();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('order.' . $this->order->driver_id);
    }

    public function broadcastAs()
    {
        return 'new.order';
    }
}
