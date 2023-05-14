<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderAvailableByIdResource;
use App\Models\Order;

class NewOrderController extends Controller
{
    public function showOrdersByDriver()
    {
        $driverId = auth()->user()->driver->id;
        $orders = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 2);
        })->get();

        return OrderAvailableByIdResource::collection($orders);

    }

    public function showOrderById($id)
    {
        $order = $this->_orderAvailable($id);

        if (!$order->exists()) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $orderAvailable = $order->get()->first();

        return new OrderAvailableByIdResource($orderAvailable);
    }

    public function updateOrderAccept($id)
    {
        $order = $this->_orderAvailable($id);

        if (!$order->exists()) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $orderAvailable = $order->get()->first();

        $orderAvailable->update([
            'status_order_id' => 3,
        ]);

        return response()->json(['success' => true, 'message' => 'Anda telah menerima pesanan']);
    }

    private function _orderAvailable($id)
    {
        $driverId = auth()->user()->driver->id;

        $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 2);
        })->where('id', $id);

        return $order;

    }

}
