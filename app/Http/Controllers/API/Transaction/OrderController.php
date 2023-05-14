<?php

namespace App\Http\Controllers\API\Transaction;

use App\Events\NewOrderNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\AvailableDriverDetailResource;
use App\Http\Resources\OrderAcceptedResource;
use App\Models\DriverDeparture;
use App\Models\LabelSeatCar;
use App\Models\Order;
use App\Models\User;

class OrderController extends Controller
{

    // Fungsi untuk menampilkan resume pesanan
    public function order_resume()
    {
        // Mendapatkan data dari session
        $driver_departure_id = session()->get('driver_departure_id');
        $seat_data = session()->get('seat_data');

        // Jika data dari session tidak ada
        if (!$driver_departure_id || !$seat_data) {
            return response()->json(['message' => 'Required data from session is missing'], 422);
        }

        // Mendapatkan data driver yang tersedia berdasarkan id
        $driverDeparture = DriverDeparture::with(['driver:id,user_id,driver_age,is_smoking', 'car:cars.id,type,production_year,seating_capacity'])->findOrFail($driver_departure_id);

        // Mendapatkan data seat car berdasarkan session seat_data
        $seatCarChoices = LabelSeatCar::select(['id', 'label_seat'])->whereIn('id', $seat_data)->get();

        // Menghitung harga sewa
        $priceOrder = count($seat_data) * 150000;

        // Memberikan respon
        return (new AvailableDriverDetailResource($driverDeparture))->additional(['seat_car_choices' => $seatCarChoices, 'price_order' => $priceOrder]);
    }

    // Fungsi untuk membuat pesanan dan tersimpan ke database
    public function set_order()
    {
        // Mengambil data dari session
        $driver_departure_id = session()->get('driver_departure_id');
        $seat_data = session()->get('seat_data');

        // Jika data dari session tidak ada
        if (!$driver_departure_id || !$seat_data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Mendapatkan user yang sedang login
        $user_id = auth()->user()->id;

        // Menghitung harga sewa
        $priceOrder = count($seat_data) * 150000;

        // Simpan pesanan ke tabel orders
        $order = Order::create([
            'user_id' => $user_id,
            'driver_departure_id' => $driver_departure_id,
            'status_order_id' => 2,
            'price_order' => $priceOrder,
        ]);

        // Mendapatkan data seat car berdasarkan session seat_data
        $seatCarChoices = LabelSeatCar::select(['id', 'label_seat'])->whereIn('id', $seat_data)->get();

        // Update data di seat car
        $seatCarChoices->each(function ($seatCarChoice) {
            $order = Order::latest()->first()->id;
            $seatCarChoice->order_id = $order;
            $seatCarChoice->is_filled = 1;
            $seatCarChoice->save();
        });

        // Kirim notifikasi ke driver terkait pesanan baru
        event(new NewOrderNotification($order));
        session()->flush();

        // Respon JSON Sukses
        return response()->json([
            'message' => 'Berhasil dipesan',
        ]);
    }

    public function showOrderAccepted()
    {
        $driverId = auth()->user()->driver->id;
        $orders = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->whereNot('status_order_id', 1)->whereNot('status_order_id', 2);
        })->get();

        return OrderAcceptedResource::collection($orders);
    }

    public function updateOrderPickLocation($id)
    {
        $driverId = auth()->user()->driver->id;

        $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 3);
        })->where('id', $id);

        if (!$order->exists()) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $orderAccepted = $order->get()->first();

        $orderAccepted->update([
            'status_order_id' => 4,
        ]);

        return response()->json(['success' => true, 'message' => 'Anda menuju ke titik jemput']);

    }

    public function updateOrderPickLocationArrive($id)
    {
        $driverId = auth()->user()->driver->id;

        $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 4);
        })->where('id', $id);

        if (!$order->exists()) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $orderAccepted = $order->get()->first();

        $orderAccepted->update([
            'status_order_id' => 5,
        ]);

        return response()->json(['success' => true, 'message' => 'Anda telah tiba di lokasi jemput']);

    }

    public function updateOrderComplete($id)
    {
        $driverId = auth()->user()->driver->id;

        $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 5);
        })->where('id', $id);

        if (!$order->exists()) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $orderAccepted = $order->get()->first();

        $orderAccepted->update([
            'status_order_id' => 6,
        ]);

        return response()->json(['success' => true, 'message' => 'Selamat! Pesanan telah selesai']);

    }
}
