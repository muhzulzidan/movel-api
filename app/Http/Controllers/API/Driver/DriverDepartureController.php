<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\LabelSeatCarResource;
use App\Models\Driver;
use App\Models\DriverDeparture;
use App\Models\Order;
use App\Models\KotaKab;
use Illuminate\Http\Request;

class DriverDepartureController extends Controller
{

    public function getRuteJadwal()
    {
        $user = auth()->user();

        // Ambil data driver berdasarkan user_id
        $driver = Driver::where('user_id', $user->id)->first();

        if (!$driver) {
            return response('Driver not found', 404);
        }

        // Ambil data rute jadwal berdasarkan driver_id
        $ruteJadwal = DriverDeparture::where('driver_id', $driver->id)
            ->join('kota_kabs as asal', 'driver_departures.kota_asal_id', '=', 'asal.id')
            ->join('kota_kabs as tujuan', 'driver_departures.kota_tujuan_id', '=', 'tujuan.id')
            ->select('driver_departures.*', 'asal.nama_kota as kota_asal', 'tujuan.nama_kota as kota_tujuan')
            ->first();

        if (!$ruteJadwal) {
            return response('Rute jadwal not found', 404);
        }

        return response($ruteJadwal, 200);
    }

    // Fungsi untuk menambah atau mengubah jadwal berangkat
    public function storeUpdateRuteJadwal(Request $request)
    {
        // Validasi inputan form rute dan jadwal
        $request->validate([
            'kota_asal_id' => 'required|exists:kota_kabs,id',
            'kota_tujuan_id' => 'required|exists:kota_kabs,id',
            'date_departure' => 'required|date',
            'time_departure' => 'required|date_format:H:i',
        ]);

        // Mengambil data user yang sudah login
        $user = auth()->user();

        // Mengambil data driver berdasarkan user_id
        $driver = Driver::where('user_id', $user->id)->first();

        // Mengambil data jadwal berangkat driver berdasarkan id driver
        $driver_departure = DriverDeparture::where('driver_id', $driver->id)->first();

        // Jika driver telah mengatur rute dan jadwal berangkat
        if ($driver_departure) {

            // Mengambil id order terkait driver departure
            $orderIds = $driver_departure->orders->pluck('id');

            $orders = Order::whereIn('id', $orderIds)->where('status_order_id', 6)->get();

            // Jika semua orderan, statusnya belum selesai
            if ($orders->count() !== $orderIds->count()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak dapat mengubah rute',
                ], 400);
            }

            //Data akan di update
            $driver_departure->update([
                'kota_asal_id' => $request->kota_asal_id,
                'kota_tujuan_id' => $request->kota_tujuan_id,
                'date_departure' => $request->date_departure,
                'time_departure' => $request->time_departure,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rute dan jadwal berhasil diperbarui',
            ]);
        }
        // Jika driver baru pertama kali mengatur rute dan jadwal
        // Data akan ditambahkan
        DriverDeparture::create([
            'driver_id' => $driver->id,
            'kota_asal_id' => $request->kota_asal_id,
            'kota_tujuan_id' => $request->kota_tujuan_id,
            'date_departure' => $request->date_departure,
            'time_departure' => $request->time_departure,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rute dan jadwal berhasil ditambahkan',
        ], 201);
    }

    public function setDriverActive()
    {
        $user = auth()->user();
        $driverDeparture = $user->driver->driver_departures->first();
        $driverDeparture->update([
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Driver Aktif.',
        ]);
    }

    public function setDriverInactive()
    {
        $user = auth()->user();
        $driverDeparture = $user->driver->driver_departures->first();
        $driverDeparture->update([
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Driver tidak aktif.',
        ]);
    }

    public function getListSeatCars()
    {
        $user = auth()->user();
        $seatCars = $user->driver->car->labelSeats;

        return LabelSeatCarResource::collection($seatCars);
    }
}
