<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use App\Models\KotaKab;
use Illuminate\Http\Request;

class KotaTujuanController extends Controller
{
    // Fungsi untuk search Kota Tujuan
    public function search_kota_tujuan(Request $request)
    {
        // Mendapatkan kata kunci pencarian dari query parameter 'q'
        $searchTerm = $request->query('q');

        // Mendapatkan id kota asal dari session
        $idKotaAsal = session()->get('kota_asal_id');

        // Jika kata kunci pencarian tidak diisi, tampilkan semua kota tujuan kecuali kota asal
        if (empty($searchTerm)) {
            $kotaTujuan = KotaKab::select('id', 'nama_kota')
                ->where('id', '<>', $idKotaAsal) // Menambahkan kondisi where untuk menghindari kota asal yang telah dipilih
                ->orderBy('id')
                ->get();
        } else {
            // Jika kata kunci pencarian diisi, filter kota tujuan berdasarkan nama kota yang cocok dengan kata kunci pencarian
            $kotaTujuan = KotaKab::select('id', 'nama_kota')
                ->where('nama_kota', 'like', '%' . $searchTerm . '%')
                ->where('id', '<>', $idKotaAsal) // Menambahkan kondisi where untuk menghindari kota asal yang telah dipilih
                ->get();
        }

        // Mengembalikan data kota tujuan dalam bentuk JSON
        return response()->json([
            'success' => true,
            'data' => $kotaTujuan,
        ]);
    }

    // Fungsi untuk mengambil 3 Kota Tujuan
    public function three_kota_tujuan()
    {
        $threeKotaTujuan = KotaKab::select('id', 'nama_kota')->orderBy('nama_kota', 'DESC')->limit(3)->get();

        // Mengembalikan 3 data berupa nama_kota
        return response()->json([
            'success' => true,
            'data' => $threeKotaTujuan,
        ]);
    }

    // Fungsi untuk menyimpan Kota Tujuan ke session
    // ketika tombol Oke di UI diklik
    public function set_kota_tujuan(Request $request)
    {
        // Validasi ketika inputan kosong
        $request->validate([
            // 'id' => 'required',
            'id' => 'required|numeric',
        ]);

        // Mengambil data
        $kotaTujuan = KotaKab::select('id', 'nama_kota')->findOrFail($request->id);

        // Data id kota asal yang dipilih disimpan sementara di session
        session()->put('kota_tujuan_id', $kotaTujuan->id);

        // Mengembalikan data berupa nama_kota yang terpilih
        return response()->json([
            'success' => true,
            'data' => $kotaTujuan,
        ]);
    }
}
