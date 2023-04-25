<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\AvailableDriverDetailResource;
use App\Http\Resources\AvailableDriverResource;
use App\Models\DriverDeparture;
use App\Models\Rating;
use App\Models\TimeDeparture;
use Illuminate\Http\Request;

class AvailableDriverController extends Controller
{

    // Fungsi untuk mendapatkan data driver yang tersedia
    public function driver_available()
    {
        // Validasi terhadap data yang diperlukan dari session
        $date_time = session()->get('date_time');
        $kota_asal_id = session()->get('kota_asal_id');
        $kota_tujuan_id = session()->get('kota_tujuan_id');
        $date_departure = $date_time['date_departure'] ?? null;
        $time_departure_id = $date_time['time_departure_id'] ?? null;

        if (!$date_departure || !$time_departure_id || !$kota_asal_id || !$kota_tujuan_id) {
            return response()->json(['message' => 'Required data from session is missing'], 422);
        }

        // Mengambil data rentang waktu berdasarkan time_departure_id
        $time_departure = TimeDeparture::findOrFail($time_departure_id);
        $hour_start = $time_departure->hour_start;
        $hour_end = $time_departure->hour_end;

        // Mengambil data keberangkatan sopir berdasarkan keberangkatan penumpang
        $driver = DriverDeparture::with(['driver:id,user_id,is_smoking', 'car:cars.id,type,production_year,seating_capacity'])->where('kota_asal_id', $kota_asal_id)
            ->where('kota_tujuan_id', $kota_tujuan_id)
            ->where('date_departure', $date_departure)
            ->whereBetween('time_departure', [$hour_start, $hour_end])
            ->get();

        // Mengecek apakah data driver ditemukan atau tidak
        if ($driver->isEmpty()) {
            session()->put('driver_available_ids', null);
            return response()->json(['message' => 'Sopir tidak tersedia'], 404);
        } else {
            // Menyimpan data id drivers yang tersedia ke session dalam array
            $driver_available_ids = $driver->pluck('id')->toArray();
            session()->put('driver_available_ids', $driver_available_ids);
        }

        // Mengembalikan response JSON dengan API Resource
        return AvailableDriverResource::collection($driver);
    }

    // Fungsi untuk mendapatkan data driver yang tersedia berdasarkan id
    public function driver_available_show($id)
    {
        // Mengambil data id drivers yang tersedia dari session dalam array
        $driver_available_ids = session()->get('driver_available_ids');

        // Validasi apakah id driver tersedia dalam session
        if (!$driver_available_ids || !in_array($id, $driver_available_ids)) {
            return response()->json(['message' => 'Data driver tidak tersedia'], 404);
        }

        // Mengambil ketersediaan sopir berdasarkan id driver_departures
        $driverDeparture = DriverDeparture::with(['driver:id,user_id,driver_age,is_smoking', 'car:cars.id,type,production_year,seating_capacity'])->findOrFail($id);

        // Mengambil rata-rata rating dari driver yang bersangkutan
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

    public function set_driver_available(Request $request)
    {
        // Mengambil data id driver_departures yang dipilih dari request
        $driver_departure_id = $request->input('driver_departure_id');

        // Validasi apakah id driver tersedia dalam session
        $driver_available_ids = session()->get('driver_available_ids');
        if (!$driver_available_ids || !in_array($driver_departure_id, $driver_available_ids)) {
            return response()->json(['message' => 'Data driver tidak tersedia'], 404);
        }

        // Menyimpan id driver_departures yang dipilih ke session
        session()->put('driver_departure_id', $driver_departure_id);

        // Mengembalikan data berupa driver yang terpilih
        return response()->json([
            'success' => true,
            'message' => "Set driver successfull",
            'data' => [
                'driver_departure_id' => session()->get('driver_departure_id'),
            ],
        ]);
    }
}
