<?php

namespace App\Http\Controllers\API\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Resources\KotaKabResource;
use App\Models\KotaKab;
use Illuminate\Http\Request;

class KotaKabController extends Controller
{

    public function index()
    {
        $kotaKab = KotaKab::all();

        return response()->json([
            'success' => true,
            'data' => $kotaKab,
        ]);
    }

    // Fungsi untuk search Kota Kabupaten
    public function search_kota_kab(Request $request)
    {
        // Mendapatkan kata kunci pencarian dari query parameter 'q'
        $searchTerm = $request->query('q');

        // Jika kata kunci pencarian tidak diisi, tampilkan semua kota
        if (empty($searchTerm)) {
            $kotaKabs = KotaKab::orderBy('id')->get();
        } else {
            // Jika kata kunci pencarian diisi, filter kota asal berdasarkan nama kota yang cocok dengan kata kunci pencarian
            $kotaKabs = KotaKab::where('nama_kota', 'like', '%' . $searchTerm . '%')->get();
        }

        // Mengembalikan data kota dengan API Resource
        return KotaKabResource::collection($kotaKabs);

    }

}
