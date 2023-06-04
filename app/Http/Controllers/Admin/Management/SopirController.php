<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Models\User;


class SopirController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $token = User::where('id', $user->id)->pluck('token');
        $response = Http::withToken($token[0])->get('https://api.movel.id/api/user/all_drivers');
        $drivers = $response->getBody()->getContents();
        $drivers = json_decode($drivers);
        // dd($drivers);
        return view('management.sopir.sopir', ['drivers' => $drivers]);
    }

    public function show($id)
    {
        $user = auth()->user();
        $token = User::where('id', $user->id)->pluck('token');
        $response = Http::withToken($token[0])->get('https://api.movel.id/api/user/all_drivers/' . $id);
        $show_sopir  = $response->json();
        return view('management.sopir.show_sopir', ['show_sopir ' => $show_sopir ]);
    }

    public function addSopirView()
    {
        return view('management.sopir.add_sopir');
    }
    public function addSopir(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'no_hp' => $request->input('no_hp'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
            'role_id' => $request->input('role_id'),
            'photo' => $request->input('photo'),
            'address' => $request->input('address'),
            'is_smoking' => $request->input('is_smoking'),
            'driver_age' => $request->input('driver_age'),
            'no_ktp' => $request->input('no_ktp'),
            'foto_ktp' => $request->input('foto_ktp'),
            'foto_sim' => $request->input('foto_sim'),
            'foto_stnk' => $request->input('foto_stnk')
        ];

        $user = auth()->user();
        $token = User::where('id', $user->id)->pluck('token');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = $photo->getClientOriginalName();
            $photoPath = $photo->getPathname();
        }
        if ($request->hasFile('foto_ktp')) {
            $foto_ktp = $request->file('foto_ktp');
            $foto_ktpName = $foto_ktp->getClientOriginalName();
            $foto_ktpPath = $foto_ktp->getPathname();
        }
        if ($request->hasFile('foto_sim')) {
            $foto_sim = $request->file('foto_sim');
            $foto_simName = $foto_sim->getClientOriginalName();
            $foto_simPath = $foto_sim->getPathname();
        }
        if ($request->hasFile('foto_stnk')) {
            $foto_stnk = $request->file('foto_stnk');
            $foto_stnkName = $foto_stnk->getClientOriginalName();
            $foto_stnkPath = $foto_stnk->getPathname();
        }

        // Mengirim permintaan POST untuk menambahkan data
        $photo = fopen($photoPath, 'r');
        $foto_ktp = fopen($foto_ktpPath, 'r');
        $foto_sim = fopen($foto_simPath, 'r');
        $foto_stnk = fopen($foto_stnkPath, 'r');
        $response = Http::attach(
            'attachment', $photo, $photoName,
            'attachment', $foto_ktp, $foto_ktpName,
            'attachment', $foto_sim, $foto_simName,
            'attachment', $foto_stnk, $foto_stnkName,
        )->withToken($token[0])->post('https://api.movel.id/api/user/driver/store', $data);

        // Mendapatkan status kode respons
        $statusCode = $response->status();

        if ($statusCode === 201) {
            // Data berhasil ditambahkan
            session()->flash('success', 'Sopir ' . $data['name'] . ' Berhasil Ditambahkan');
        } else {
            // Terjadi kesalahan
            session()->flash('error', 'Sopir ' . $data['name'] . ' Gagal Ditambahkan');
        }
        return view('management.sopir.sopir');
    }

    public function editSopir($id)
    {
        return view('management.sopir.edit_sopir');
    }

    public function deleteSopir()
    {
        return view('management.sopir.add_sopir');
    }
}
