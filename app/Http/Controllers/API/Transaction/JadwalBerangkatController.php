<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalBerangkatController extends Controller
{
    public function set_jadwal_berangkat(Request $request)
    {
        // Validasi inputan tanggal dan jam
        $request->validate([
            'date_departure' => 'required|date',
            'hours_departure' => 'required|date_format:H:i',
        ]);

        //Menyimpan data ke session
        session()->put('jadwal_berangkat', [
            'date_departure' => $request->date_departure,
            'hours_departure' => $request->hours_departure,
        ]);

        // Mengembalikan data berupa jadwal berangkat yang telah ditentukan
        return response()->json([
            'success' => true,
            'data' => session()->get('jadwal_berangkat'),
        ]);

    }
}
