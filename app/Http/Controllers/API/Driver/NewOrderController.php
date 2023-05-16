<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderAvailableByIdResource;
use App\Models\Order;

class NewOrderController extends Controller
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

    // Mengambil data masing masing pesanan berdasarkan id
    public function showOrderById($id)
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
    public function updateOrderAccept($id)
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
