<?php

namespace App\Http\Controllers\API\Transaction;

use App\Events\NewOrderNotification;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Transaction\RiwayatPesananController;
use App\Http\Resources\AvailableDriverDetailResource;
use App\Http\Resources\AvailableDriverResource;
use App\Http\Resources\LabelSeatCarResource;
use App\Models\Car;
use App\Models\Balance;
use App\Models\DriverDeparture;
use App\Models\LabelSeatCar;
use App\Models\Order;
use App\Models\StatusOrder;
use App\Models\Rating;
use App\Models\TimeDeparture;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{

    public function getDriverAvailable(Request $request)
    {
        $request->validate([
            'kota_asal_id' => 'required|exists:kota_kabs,id',
            'kota_tujuan_id' => 'required|exists:kota_kabs,id',
            'date_departure' => 'required|date',
            'time_departure_id' => 'required|exists:time_departures,id',
        ]);

        $kotaAsalId = $request->kota_asal_id;
        $kotaTujuanId = $request->kota_tujuan_id;
        $dateDeparture = $request->date_departure;
        $timeDepartureId = $request->time_departure_id;

        // Mengambil data rentang waktu berdasarkan time_departure_id
        $timeDeparture = TimeDeparture::findOrFail($timeDepartureId);
        $hour_start = $timeDeparture->hour_start;
        $hour_end = $timeDeparture->hour_end;

        $driverDepartures = DriverDeparture::where('is_active', true)->where('kota_asal_id', $kotaAsalId)
            ->where('kota_tujuan_id', $kotaTujuanId)
            ->where('date_departure', $dateDeparture)
            ->whereBetween('time_departure', [$hour_start, $hour_end])
            ->get();

        if ($driverDepartures->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Sopir tidak tersedia',
            ], 404);
        }

        return AvailableDriverResource::collection($driverDepartures);
    }

    public function getDriverAvailableById($id)
    {

        $driverDeparture = DriverDeparture::where('id', $id);

        if (!$driverDeparture->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $driverDeparture = $driverDeparture->get()->first();

        // Mengambil rata-rata rating dari driver yang bersangkutan {
        $driverRating = Rating::where('driver_id', $driverDeparture->driver_id)->avg('nilai_rating');

        // Jika tidak ada rating maka di set jadi 0
        if ($driverRating == null) {
            $driverRating = 0;
        }

        // Mengembalikan data JSON sopir tersedia beserta rating rata-rata
        return (new AvailableDriverDetailResource($driverDeparture))->additional([
            'data' => ['rating' => $driverRating],
        ]);
    }

    public function getSeatCars($id)
    {
        $driverDeparture = DriverDeparture::where('id', $id);

        if (!$driverDeparture->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $driverDeparture = $driverDeparture->get()->first();

        return (new AvailableDriverDetailResource($driverDeparture))->additional(['data' => ['label_seats' => LabelSeatCarResource::collection($driverDeparture->car->labelSeats)]]);
    }

    public function getOrderResume(Request $request)
    {
        $request->validate([
            'driver_departure_id' => 'required|exists:driver_departures,id',
            'seat_car_choices' => [
                'required',
                'array',
                Rule::exists('label_seat_cars', 'id')->where(function ($query) use ($request) {
                    $driverDepartureId = $request->driver_departure_id;
                    $driverId = DriverDeparture::findOrFail($driverDepartureId)->driver_id;
                    $carId = Car::where('driver_id', $driverId)->value('id');
                    $query->where('car_id', $carId);
                }),
            ],
        ]);

        $driverDepartureId = $request->driver_departure_id;
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

        return (new AvailableDriverDetailResource($driverDeparture))->additional(['data' => ['seat_car_choices' => $seatCarChoices, 'price_order' => $priceOrder]]);
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'driver_departure_id' => 'required|exists:driver_departures,id',
            'seat_car_choices' => [
                'required',
                'array',
                Rule::exists('label_seat_cars', 'id')->where(function ($query) use ($request) {
                    $driverDepartureId = $request->driver_departure_id;
                    $driverId = DriverDeparture::findOrFail($driverDepartureId)->driver_id;
                    $carId = Car::where('driver_id', $driverId)->value('id');
                    $query->where('car_id', $carId);
                }),
            ],
        ]);

        $userId = auth()->user()->id;
        $driverDepartureId = $request->driver_departure_id;
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

        $order = Order::create([
            'user_id' => $userId,
            'driver_departure_id' => $driverDepartureId,
            'status_order_id' => 2,
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

        event(new NewOrderNotification($order));

        return response()->json([
            'success' => true,
            'message' => 'Berhasil dipesan',
        ]);
    }

    public function updateOrderCancelled($id)
    {
        $driverId = auth()->user()->driver->id;

        $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 8);
        })->where('id', $id);

        // Jika data tidak ada
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        // Jika data ada
        $orderAccepted = $order->get()->first();
        // Update tabel orders
        $orderAccepted->update([
            'status_order_id' => 9,
        ]);
        return response()->json(['success' => true, 'message' => 'Setuju pembatalan pesanan']);
    }

    public function updateOrderNotCancelled($id)
    {
        $driverId = auth()->user()->driver->id;

        $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 9);
        })->where('id', $id);

        // Jika data tidak ada
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        // Jika data ada
        $orderAccepted = $order->get()->first();
        // Update tabel orders
        $orderAccepted->update([
            'status_order_id' => 3,
        ]);
        return response()->json(['success' => true, 'message' => 'Menolak pembatalan pesanan']);
    }

    // Update status driver menuju ke lokasi jemput
    public function updateOrderPickLocation($id)
    {
        $driverId = auth()->user()->driver->id;

        $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 3);
        })->where('id', $id);

        // Jika data tidak ada
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        // Jika data ada
        $orderAccepted = $order->get()->first();
        // Update tabel orders
        $orderAccepted->update([
            'status_order_id' => 5,
        ]);
        return response()->json(['success' => true, 'message' => 'Anda menuju ke titik jemput']);
    }

    // Update status driver tiba dilokasi
    public function updateOrderPickLocationArrive($id)
    {
        $driverId = auth()->user()->driver->id;

        $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 5);
        })->where('id', $id);

        // Jika data tidak ada
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        // Jika data ada
        $orderAccepted = $order->get()->first();
        // Update data tabel orders
        $orderAccepted->update([
            'status_order_id' => 6,
        ]);
        return response()->json(['success' => true, 'message' => 'Anda telah tiba di lokasi jemput']);
    }


    // Perbarui status_orders_id menjadi 4 (Sopir Berangkat)
    public function updateOrderDriverDeparture($id)
    {
        $order = Order::find($id);
        if ($order) {
            $sopirBerangkatStatus = StatusOrder::find(4);
            if ($sopirBerangkatStatus) {
                $order->status_orders_id = $sopirBerangkatStatus->id;
                $order->save();

                return response()->json([
                    'message' => 'Berangkat berhasil diupdate.'
                ]);
            } else {
                return response()->json(['message' => 'Status Sopir Berangkat tidak ditemukan.'], 404);
            }
        } else {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }
    }

    // Update status pesanan selesai oleh driver
    // public function updateOrderComplete($id)
    // {
    //     $driverId = auth()->user()->driver->id;

    //     $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
    //         $query->where('driver_id', $driverId)->where('status_order_id', 6);
    //     })->where('id', $id);

    //     // Jika datanya tidak ada
    //     if (!$order->exists()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Order tidak ditemukan'
    //         ], 404);
    //     }

    //     // Jika data ada
    //     $orderAccepted = $order->first();

    //     // Update data tabel orders
    //     $orderAccepted->update([
    //         'status_order_id' => 7,
    //     ]);

    //     // Kurangi saldo sebesar 5000
    //     $currentBalance = Balance::where('driver_id', $driverId)->first();

    //     if ($currentBalance) {
    //         $currentBalance->saldo -= 5000;
    //         $currentBalance->save();
    //     }

    //     // Panggil fungsi untuk memindahkan data ke tabel riwayat_pesanan
    //     $riwayatPesananController = new RiwayatPesananController();
    //     $riwayatPesananController->moveCompletedOrdersToHistory();

    //     LabelSeatCar::where('order_id', $orderAccepted->id)->update(['is_filled' => 0]);
    //     LabelSeatCar::where('order_id', $orderAccepted->id)->update(['order_id' => null]);

    //     // Reset kolom date_departure dan time_departure, serta set is_active menjadi 0 pada tabel DriverDeparture
    //     $driverDeparture = $orderAccepted->driverDeparture;
    //     $driverDeparture->update([
    //         'date_departure' => null,
    //         'time_departure' => null,
    //         'is_active' => 0,
    //     ]);

    //     return response()->json(['success' => true, 'message' => 'Selamat! Pesanan telah selesai']);
    // }

    public function updateOrderComplete($id)
    {
        $driverId = auth()->user()->driver->id;

        $order = Order::whereHas('driverDeparture', function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->where('status_order_id', 6);
        })->where('id', $id);

        // Jika datanya tidak ada
        if (!$order->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        // Jika data ada
        $orderAccepted = $order->first();

        // Update data tabel orders
        $orderAccepted->update([
            'status_order_id' => 7,
        ]);

        // Kurangi saldo sebesar 5000
        $currentBalance = Balance::where('driver_id', $driverId)->first();

        if ($currentBalance) {
            $currentBalance->saldo -= 5000;
            $currentBalance->save();
        }

        LabelSeatCar::where('order_id', $orderAccepted->id)->update(['is_filled' => 0]);
        LabelSeatCar::where('order_id', $orderAccepted->id)->update(['order_id' => null]);

        // Periksa apakah masih ada pesanan dengan driver_departure_id yang sama dan status_order_id bukan 7
        $pendingOrders = Order::where('driver_departure_id', $orderAccepted->driver_departure_id)
            ->where('status_order_id', '<>', 7)
            ->exists();

        // Jika masih ada pesanan yang belum selesai, tidak perlu mengubah DriverDeparture
        if ($pendingOrders) {
            return response()->json([
                'status' => true,
                'message' => 'Pesanan untuk penumpang ini telah selesai, Masih ada pesanan lain yang belum selesai'
            ]);
        }

        // Reset kolom date_departure dan time_departure, serta set is_active menjadi 0 pada tabel DriverDeparture
        $driverDeparture = $orderAccepted->driverDeparture;
        $driverDeparture->update([
            'kota_asal_id' => null,
            'kota_tujuan_id' => null,
            'date_departure' => null,
            'time_departure' => null,
            'is_active' => 0,
        ]);

        // Panggil fungsi untuk memindahkan data ke tabel riwayat_pesanan
        // $riwayatPesananController = new RiwayatPesananController();
        // $riwayatPesananController->moveCompletedOrdersToHistory();

        return response()->json(['success' => true, 'message' => 'Selamat! Semua pesanan telah selesai']);
    }
}
