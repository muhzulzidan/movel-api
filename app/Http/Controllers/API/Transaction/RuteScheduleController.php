<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RuteScheduleController extends Controller
{
    public function set_kota_asal(Request $request)
    {
        // Validasi form inputan
        $request->validate([
            'kota_asal_id' => 'required|exists:kota_kabs,id',
        ]);

        // Data id kota asal yang dipilih disimpan sementara di session
        session()->put('kota_asal_id', $request->kota_asal_id);

        // Mengembalikan data berupa nama_kota yang terpilih
        return response()->json([
            'success' => true,
            'message' => "Set kota asal successfull",
            'data' => [
                'kota_asal_id' => session()->get('kota_asal_id'),
            ],
        ]);
    }

    public function set_kota_tujuan(Request $request)
    {
        // Validasi form inputan
        $request->validate([
            'kota_tujuan_id' => 'required|exists:kota_kabs,id',
        ]);

        // Data id kota tujuan yang dipilih disimpan sementara di session
        session()->put('kota_tujuan_id', $request->kota_tujuan_id);

        // Mengembalikan data berupa nama_kota yang terpilih
        return response()->json([
            'success' => true,
            'message' => "Set kota tujuan successfull",
            'data' => [
                'kota_tujuan_id' => session()->get('kota_tujuan_id'),
            ],
        ]);

    }

    public function set_date_time(Request $request)
    {
        // Validasi inputan tanggal dan waktu
        $request->validate([
            'date_departure' => 'required|date',
            'time_departure_id' => 'required|exists:time_departures,id',
        ]);

        //Menyimpan data ke session
        session()->put('date_time', [
            'date_departure' => $request->date_departure,
            'time_departure_id' => $request->time_departure_id,
        ]);

        // Mengembalikan data berupa date and time yang telah ditentukan
        return response()->json([
            'success' => true,
            'data' => session()->get('date_time'),
        ]);
    }
}
