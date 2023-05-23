<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\AvailableDriverDetailResource;
use App\Http\Resources\AvailableDriverResource;
use App\Http\Resources\LabelSeatCarResource;
use App\Models\Car;
use App\Models\DriverDeparture;
use App\Models\LabelSeatCar;
use App\Models\Rating;
use App\Models\TimeDeparture;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AvailableDriverController extends Controller
{

    // Fungsi untuk mendapatkan data driver yang tersedia
    public function driver_available()
    {
        // return session()->flush();

        // Validasi terhadap data yang diperlukan dari session
        $date_time = session()->get('date_time');
        $kota_asal_id = session()->get('kota_asal_id');
        $kota_tujuan_id = session()->get('kota_tujuan_id');

        // return session()->all();

        // Jika session tidak ada
        if (!$date_time || !$kota_asal_id || !$kota_tujuan_id) {
            return response()->json(['success' => false, 'message' => 'Pastikan kota asal, kota tujuan dan jadwal telah diatur'], 400);
        }

        // Mengambil data rentang waktu berdasarkan time_departure_id
        $time_departure = TimeDeparture::findOrFail($date_time['time_departure_id']);
        $hour_start = $time_departure->hour_start;
        $hour_end = $time_departure->hour_end;

        // Mengambil data keberangkatan sopir berdasarkan keberangkatan penumpang
        $driverDepartures = DriverDeparture::where('kota_asal_id', $kota_asal_id)
            ->where('kota_tujuan_id', $kota_tujuan_id)
            ->where('date_departure', $date_time['date_departure'])
            ->whereBetween('time_departure', [$hour_start, $hour_end])
            ->get();

        // Mengecek apakah data driver ditemukan atau tidak
        if ($driverDepartures->isEmpty()) {
            // session()->put('driver_available_ids', null);
            return response()->json(['success' => false, 'message' => 'Sopir tidak tersedia'], 404);
        }

        // Menyimpan data id drivers yang tersedia ke session dalam array
        $driver_available_ids = $driverDepartures->pluck('id')->toArray();
        session()->put('driver_available_ids', $driver_available_ids);

        return AvailableDriverResource::collection($driverDepartures);
    }

    // Fungsi untuk mendapatkan data driver yang tersedia berdasarkan id
    public function driver_available_show($id)
    {
        // Mengambil data id drivers yang tersedia dari session dalam array
        $driver_available_ids = session()->get('driver_available_ids');

        // Validasi apakah id driver tersedia dalam session
        if (!$driver_available_ids || !in_array($id, $driver_available_ids)) {
            return response()->json(['success' => false, 'message' => 'Data driver tidak tersedia'], 404);
        }

        // Mengambil ketersediaan sopir berdasarkan id driver_departures
        $driverDeparture = DriverDeparture::findOrFail($id);

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
            return response()->json(['success' => false, 'message' => 'Data driver tidak tersedia'], 404);
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

    public function list_seat_car()
    {
        // Mengambil id data driver_departure
        $driver_departure_id = session()->get('driver_departure_id');

        // Jika tidak ditemukan datanya
        if (!$driver_departure_id) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Mengambil id driver berdasarkan id driver_departure
        $driverDeparture = DriverDeparture::select('driver_id')->findOrFail($driver_departure_id);

        // Mengambil id car berdasarkan driver_id
        $car = Car::select('id')->where('driver_id', $driverDeparture->driver_id)->get()->first();

        // Mendapatkan data seat car berdasarkan car id
        $label_seat_car = LabelSeatCar::where('car_id', $car->id)->get();

        return LabelSeatCarResource::collection($label_seat_car);
    }

    public function set_seat_car(Request $request)
    {
        // Mengambil id data driver_departure
        $driver_departure_id = session()->get('driver_departure_id');

        // Jika tidak ditemukan datanya
        if (!$driver_departure_id) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // validasi inputan
        $request->validate([
            'seat_car_ids' => [
                'required',
                'array',
                Rule::exists('label_seat_cars', 'id')->where(function ($query) use ($request) {
                    $driver_departure_id = session()->get('driver_departure_id');
                    $driver_id = DriverDeparture::findOrFail($driver_departure_id)->driver_id;
                    $car_id = Car::where('driver_id', $driver_id)->value('id');
                    $query->where('car_id', $car_id);
                }),
            ],
        ]);

        // Mengambil id driver berdasarkan id driver_departure
        $driver = DriverDeparture::select('driver_id')->findOrFail($driver_departure_id);

        // Mengambil id car berdasarkan driver_id
        $car = Car::select('id')->where('driver_id', $driver->driver_id)->get()->first();

        // Mengambil data inputan yang lolos
        $seatCarIds = $request->seat_car_ids;

        // Mendapatkan semua data seat car yang ada dalam array yang cocok
        $carSeats = LabelSeatCar::whereIn('id', $seatCarIds)->where('car_id', $car->id)->get();

        // Mengecek kursi yang sudah terpesan
        foreach ($carSeats as $carSeat) {
            if ($carSeat->is_filled == 1) {
                return response()->json(['success' => false, 'message' => 'Ada kursi sudah terpilih'], 422);
            }
        }

        // Menyimpan data pilihan kursi pengguna ke dalam session
        session()->put('seat_data', $seatCarIds);

        // Respon jika kursi berhasil di pilih
        return response()->json([
            'success' => true,
            'message' => 'Kursi berhasil dipilih',
        ]);
    }
}
