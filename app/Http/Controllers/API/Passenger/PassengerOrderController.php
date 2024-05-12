<?php

namespace App\Http\Controllers\API\Passenger;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailOrderPassengerResource;
use App\Http\Resources\OrderPassengerResource;
use App\Http\Resources\OrderStatusResource;
use App\Models\Order;

class PassengerOrderController extends Controller
{
    public function passengerOrderStatus()
    {
        $user = auth()->user();
        $order = Order::where('user_id', $user->id)->latest()->firstOrFail();
        return new OrderStatusResource($order);
    }

    public function setCancelPassengerOrder()
    {
        $user = auth()->user();
        $order = Order::where('user_id', $user->id)->where('status_order_id', 3);

        if ($order->get()->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat batalkan pesanan!',
            ], 400);
        }

        $order->get()->first()->update(['status_order_id' => 8]);

        return response()->json([
            'success' => true,
            'message' => 'Pembatalan sedang ditinjau!',
        ]);
    }

    public function showListPassengerOrder()
    {
        $user = auth()->user();
        $orders = $user->orders->where('status_order_id', 7);

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 404);
        }

        return OrderPassengerResource::collection($orders);
    }

    public function detailPassengerOrder($id)
    {
        $user = auth()->user();
        $order = $user->orders->where('status_order_id', 7)->where('is_rating', 1)->where('id', $id);

        if ($order->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 404);
        }

        return new DetailOrderPassengerResource($order->first());
    }
}
