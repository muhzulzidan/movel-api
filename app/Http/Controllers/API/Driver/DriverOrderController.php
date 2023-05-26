<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailOrderDriverResource;
use App\Http\Resources\OrderAcceptedResource;
use App\Http\Resources\OrderAvailableByIdResource;
use App\Http\Resources\OrderDriverRejectedResource;
use App\Http\Resources\OrderDriverResource;
use App\Models\Order;

class DriverOrderController extends Controller
{
    // Mengambil data pesanan yang masuk ke driver
    public function showOrdersByDriver()
    {
        $driverId = auth()->user()->driver->id;
        $orders = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 2);
        })->get();

        // Jika datanya kosong
        if ($orders->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Order tidak ditemukan',
            ], 404);
        }

        // Jika datanya ada
        return OrderAvailableByIdResource::collection($orders);
    }

    public function showDriverOrderById($id)
    {
        // Memanggil fungsi order yang tersedia
        $order = $this->_orderAvailable($id);

        // Jika data tidak ada
        if (!$order->exists()) {
            return response()->json(['status' => false,
                'message' => 'Order tidak ditemukan'], 404);
        }

        // Jika data ada
        $orderAvailable = $order->get()->first();
        return new OrderAvailableByIdResource($orderAvailable);
    }

    // Mengubah status atau driver menerima pesanan
    public function updateDriverOrderAccept($id)
    {
        // Memanggil fungsi order yang tersedia
        $order = $this->_orderAvailable($id);

        // Jika pesanan tidak ada
        if (!$order->exists()) {
            return response()->json(['status' => false,
                'message' => 'Order tidak ditemukan'], 404);
        }

        // Jika pesanan ada
        $orderAvailable = $order->get()->first();

        // Update data pada tabel orders
        $orderAvailable->update([
            'status_order_id' => 3,
        ]);

        return response()->json(['success' => true, 'message' => 'Anda telah menerima pesanan']);
    }

    // Mengubah status atau driver menolak pesanan
    public function updateDriverOrderReject($id)
    {
        // Memanggil fungsi order yang tersedia
        $order = $this->_orderAvailable($id);

        // Jika pesanan tidak ada
        if (!$order->exists()) {
            return response()->json(['status' => false,
                'message' => 'Order tidak ditemukan'], 404);
        }

        // Jika pesanan ada
        $orderAvailable = $order->get()->first();

        // Update data pada tabel orders
        $orderAvailable->update([
            'status_order_id' => 1,
        ]);

        return response()->json(['success' => true, 'message' => 'Anda telah menolak pesanan']);
    }

    // Mengambil daftar pesanan yang telah diterima driver
    public function showDriverOrderAccepted()
    {
        $driverId = auth()->user()->driver->id;
        $orders = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->whereNot('status_order_id', 1)->whereNot('status_order_id', 2);
        })->get();

        // Jika data tidak ada
        if ($orders->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Order tidak ditemukan',
            ], 404);
        }

        // Jika datanya ada
        return OrderAcceptedResource::collection($orders);
    }

    // Mengambil daftar pesanan yang telah diterima driver
    public function showDriverOrderRejected()
    {
        $driverId = auth()->user()->driver->id;
        $orders = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 1);
        })->get();

        // Jika data tidak ada
        if ($orders->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Order tidak ditemukan',
            ], 404);
        }

        // Jika datanya ada
        return OrderAcceptedResource::collection($orders);
    }

    public function detailRejectedDriverOrder($id)
    {
        $user = auth()->user();
        $driverDepartureId = $user->driver->driver_departures->first()->id;

        $order = Order::where('status_order_id', 1)->where('driver_departure_id', $driverDepartureId)->where('id', $id)->get();

        if ($order->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 400);
        }

        return new OrderDriverRejectedResource($order->first());
    }

    public function showListCompletedDriverOrders()
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

    public function detailCompletedDriverOrder($id)
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

    // fungsi privat untuk data order berdasarkan id
    private function _orderAvailable($id)
    {
        $driverId = auth()->user()->driver->id;

        $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 2);
        })->where('id', $id);

        return $order;

    }
}
