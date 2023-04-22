<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use App\Models\KotaKab;
use Illuminate\Http\Request;

class KotaAsalController extends Controller
{
    // Fungsi untuk search Kota Asal
    public function search_kota_asal(Request $request)
    {
        // Mendapatkan kata kunci pencarian dari query parameter 'q'
        $searchTerm = $request->query('q');

        // Jika kata kunci pencarian tidak diisi, tampilkan semua kota asal
        if (empty($searchTerm)) {
            $kotaAsal = KotaKab::select('id', 'nama_kota')->orderBy('id')->get();
        } else {
            // Jika kata kunci pencarian diisi, filter kota asal berdasarkan nama kota yang cocok dengan kata kunci pencarian
            $kotaAsal = KotaKab::select('id', 'nama_kota')->where('nama_kota', 'like', '%' . $searchTerm . '%')->get();
        }

        // Mengembalikan data kota asal dalam bentuk JSON
        return response()->json([
            'success' => true,
            'data' => $kotaAsal,
        ]);
    }

    // Fungsi untuk mengambil 3 Kota Asal
    public function three_kota_asal()
    {
        $threeKotaAsal = KotaKab::select('id', 'nama_kota')->orderBy('nama_kota', 'ASC')->limit(3)->get();

        // Mengembalikan 3 data berupa nama_kota
        return response()->json([
            'success' => true,
            'data' => $threeKotaAsal,
        ]);

    }

    // Fungsi untuk menyimpan Kota Asal ke session
    // ketika tombol Oke di UI diklik
    public function set_kota_asal(Request $request)
    {
        // Validasi ketika inputan kosong
        $request->validate([
            'id' => 'required|numeric',
        ]);

        // Mengambil data
        $kotaAsal = KotaKab::select('id', 'nama_kota')->findOrFail($request->id);

        // Data id kota asal yang dipilih disimpan sementara di session
        session()->put('kota_asal_id', $kotaAsal->id);

        // Mengembalikan data berupa nama_kota yang terpilih
        return response()->json([
            'success' => true,
            'data' => $kotaAsal,
        ]);
    }
}
