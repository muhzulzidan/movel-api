<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailOrderDriverResource;
use App\Http\Resources\OrderAcceptedResource;
use App\Http\Resources\OrderAvailableByIdResource;
use App\Http\Resources\OrderDriverRejectedResource;
use App\Http\Resources\OrderDriverResource;
use App\Models\Car;
use App\Models\DriverDeparture;
use App\Models\LabelSeatCar;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function addPassengerByDriver(Request $request)
    {

        $request->validate([
            'seat_car_choices' => [
                'required',
                'array',
                Rule::exists('label_seat_cars', 'id')->where(function ($query) use ($request) {
                    $user = auth()->user();
                    $driverId = $user->driver->id;
                    $carId = Car::where('driver_id', $driverId)->value('id');
                    $query->where('car_id', $carId);
                }),
            ],
        ]);

        $user = auth()->user();

        $driverDepartureId = $user->driver->driverDeparture->id;

        $seatCarChoices = $request->seat_car_choices;

        $driverDeparture = DriverDeparture::findOrFail($driverDepartureId);
        $car = $driverDeparture->driver->car;

        $carSeats = LabelSeatCar::whereIn('id', $seatCarChoices)->where('car_id', $car->id)->get();

        // Mengecek kursi yang sudah terpesan
        foreach ($carSeats as $carSeat) {
            if ($carSeat->is_filled == 1) {
                return response()->json(['success' => false, 'message' => 'Ada kursi sudah terpilih'], 422);
            }
        }

        $priceOrder = count($seatCarChoices) * 150000;

        Order::create([
            'user_id' => $user->id,
            'driver_departure_id' => $driverDepartureId,
            'status_order_id' => 3,
            'price_order' => $priceOrder,
        ]);

        // Mendapatkan data seat car berdasarkan session seat_data
        $labelSeatCars = LabelSeatCar::select(['id', 'label_seat'])->whereIn('id', $seatCarChoices)->get();

        // Update data di seat car
        $labelSeatCars->each(function ($seatCarChoice) {
            $order = Order::latest()->first()->id;
            $seatCarChoice->order_id = $order;
            $seatCarChoice->is_filled = 1;
            $seatCarChoice->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Penumpang berhasil ditambahkan',
        ]);

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
