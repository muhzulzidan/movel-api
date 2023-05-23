<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailOrderDriverResource;
use App\Http\Resources\OrderDriverResource;
use App\Models\Order;

class OrderDriverController extends Controller
{
    public function showListOrderDriver()
    {
        $user = auth()->user();
        $driverDepartureId = $user->driver->driver_departures->first()->id;

        $orders = Order::where('status_order_id', 6)->where('is_rating', 1)->where('driver_departure_id', $driverDepartureId)->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 400);
        }

        return OrderDriverResource::collection($orders);

    }

    public function detailOrderDriver($id)
    {
        $user = auth()->user();
        $driverDepartureId = $user->driver->driver_departures->first()->id;

        $order = Order::where('status_order_id', 6)->where('is_rating', 1)->where('driver_departure_id', $driverDepartureId)->where('id', $id)->get();

        if ($order->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 400);
        }

        return new DetailOrderDriverResource($order->first());
    }
}
