<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPesanan;
use App\Models\DriverDeparture;
use App\Models\LabelSeatCar;
use App\Models\Order;

class RiwayatPesananController extends Controller
{
    public function moveCompletedOrdersToHistory()
    {
        // Ambil pesanan yang sudah selesai (status_order_id = 7)
        $completedOrders = Order::where('status_order_id', 7)->get();

        foreach ($completedOrders as $order) {
            // Ambil data dari tabel terkait
            $driverDeparture = DriverDeparture::find($order->driver_departure_id);
            $labelSeatCars = LabelSeatCar::where('order_id', $order->id)->get();

            // Hitung total kursi dipesan
            $totalSeatsOrdered = $labelSeatCars->count();

            // Ambil data dari tabel user untuk nama driver dan passenger
            $driverName = $driverDeparture->driver->user->name;
            $passengerName = $order->user->name;

            // Gabungkan kota_asal_id dan kota_tujuan_id menjadi satu string untuk kolom tujuan
            $tujuan = $driverDeparture->kota_asal_id . ' - ' . $driverDeparture->kota_tujuan_id;

            // Simpan data ke dalam tabel riwayat_pesanan
            RiwayatPesanan::create([
                'user_id' => $order->user_id,
                'driver_id' => $order->driver_departure_id->driver_id,
                'driver' => $driverName,
                'passenger' => $passengerName,
                'total_seats_ordered' => $totalSeatsOrdered,
                'order_date' => $order->created_at->toDateString(),
                'departure_date' => $driverDeparture->date_departure,
                'departure_time' => $driverDeparture->time_departure,
                'tujuan' => $tujuan,
                'status' => 'Selesai', // Sesuaikan dengan status yang sesuai
                'harga' => $order->price_order,
            ]);

            // Optional: Hapus data dari tabel asal jika diperlukan
            // $order->delete();
            // $driverDeparture->delete();
            // $labelSeatCars->each->delete();
        }

        return response()->json(['message' => 'Data moved to history successfully']);
    }

    public function getRiwayatPesananPenumpang()
    {
        // Ambil ID penumpang dari user yang sedang login
        $userId = auth()->user()->id;

        // Ambil data dari tabel riwayat_pesanan berdasarkan ID penumpang
        $riwayatPesanan = RiwayatPesanan::where('user_id', $userId)->get();

        // Jika tidak ada data
        if ($riwayatPesanan->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data riwayat pesanan tidak ditemukan'
            ], 404);
        }

        // Jika ada data
        return response()->json([
            'status' => true,
            'data' => $riwayatPesanan
        ]);
    }

    public function getRiwayatPesananSopir()
    {
        // Ambil ID sopir dari user yang sedang login
        $driverId = auth()->user()->driver->id;

        // Ambil data dari tabel riwayat_pesanan berdasarkan ID sopir
        $riwayatPesanan = RiwayatPesanan::where('driver_id', $driverId)->get();

        // Jika tidak ada data
        if ($riwayatPesanan->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data riwayat pesanan tidak ditemukan'
            ], 404);
        }

        // Jika ada data
        return response()->json([
            'status' => true,
            'data' => $riwayatPesanan
        ]);
    }
}
