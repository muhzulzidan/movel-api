<?php

namespace App\Http\Controllers\API\Passenger;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailOrderPassengerResource;
use App\Http\Resources\OrderPassengerResource;

class OrderPassengerController extends Controller
{
    public function showListOrderPassenger()
    {
        $user = auth()->user();
        $orders = $user->orders->where('status_order_id', 6)->where('is_rating', 1);

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 400);
        }

        return OrderPassengerResource::collection($orders);
    }

    public function detailOrderPassenger($id)
    {
        $user = auth()->user();
        $order = $user->orders->where('status_order_id', 6)->where('is_rating', 1)->where('id', $id);

        if ($order->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 400);
        }

        return new DetailOrderPassengerResource($order->first());

    }
}
