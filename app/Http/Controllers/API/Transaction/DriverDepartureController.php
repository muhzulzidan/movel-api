<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;

class DriverDepartureController extends Controller
{

    // Fungsi untuk mendapatkan data driver yang tersedia
    public function driver_available()
    {
        // Pengambilan data dari session
        $jadwal_berangkat = session()->get('jadwal_berangkat');
        $kota_asal_id = session()->get('kota_asal_id');
        $kota_tujuan_id = session()->get('kota_tujuan_id');
        $date_departure = $jadwal_berangkat['date_departure'];
        $hours_departure = $jadwal_berangkat['hours_departure'];

    }
}
