<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;

class DriverDepartureController extends Controller
{

    // Fungsi untuk mendapatkan data driver yang tersedia
    public function driver_available()
    {
        // Pengambilan data dari session
        $date_time = session()->get('date_time');
        $data = array(
            'kota_asal_id' => session()->get('kota_asal_id'),
            'kota_tujuan_id' => session()->get('kota_tujuan_id'),
            'date_departure' => $date_time['date_departure'],
            'time_departure_id' => $date_time['time_departure_id'],
        );
        // $kota_asal_pass_id = session()->get('kota_asal_pass_id');
        // $kota_tujuan_pass_id = session()->get('kota_tujuan_pass_id');
        // $date_departure = $jadwal_berangkat_pass['date_departure'];
        // $hours_departure = $jadwal_berangkat_pass['time_departure_id'];

        return $data;
    }
}
