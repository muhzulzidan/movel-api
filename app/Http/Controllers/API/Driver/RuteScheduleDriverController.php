<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverDeparture;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RuteScheduleDriverController extends Controller
{

    // Fungsi untuk menambah atau mengubah jadwal berangkat
    public function store_update_rute_jadwal(Request $request)
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
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rute dan jadwal berhasil ditambahkan',
        ], 201);
    }
}
