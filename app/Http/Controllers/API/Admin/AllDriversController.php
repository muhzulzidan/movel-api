<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


class AllDriversController extends Controller
{
    public function index()
    {
        $drivers = User::join('drivers', 'drivers.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.email', 'users.no_hp', 'drivers.address', 'is_smoking', 'driver_age', 'drivers.photo', 'drivers.no_ktp', 'drivers.foto_ktp', 'drivers.foto_sim', 'drivers.foto_stnk')
            ->get();

        foreach ($drivers as $driver) {
            $driver->gambar_url = Storage::url($driver->photo);
        }

        return response($drivers, 200);
    }

    public function show($id)
    {
        $driver = User::join('drivers', 'users.id', '=', 'drivers.user_id')
            ->where('users.id', $id)
            ->select('users.*', 'drivers.*')
            ->first();

        $driver->gambar_url = Storage::url($driver->photo);

        return response($driver, 200);
    }

    public function store(Request $request) {
        // melakukan validasi inputan pada request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'no_hp' => ['required', 'string', 'max:13'],
            'password' => ['required', 'confirmed', 'min:6'],
            'role_id' => ['required'],
            'photo' => ['required', 'image', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'is_smoking' => ['required'],
            'driver_age' => ['required'],
            'no_ktp' => ['required'],
            'foto_ktp' => ['required', 'image', 'max:2048'],
            'foto_sim' => ['required', 'image', 'max:2048'],
            'foto_stnk' => ['required', 'image', 'max:2048'],
        ]);

        // Cek apakah ada foto yang di-upload
        if ($request->hasFile('photo')) {
            // Simpan foto pada folder public dengan nama asli
            $photo = $request->file('photo');
            $filename_photo = date('YmdHis') . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/photo', $filename_photo);
        }

        // Cek apakah ada foto KTP yang di-upload
        if ($request->hasFile('foto_ktp')) {
            // Simpan foto pada folder public dengan nama asli
            $ktp = $request->file('foto_ktp');
            $filename_ktp = date('YmdHis') . '_' . $ktp->getClientOriginalName();
            $ktp->storeAs('public/KTP', $filename_ktp);
        }

        // Cek apakah ada foto SIM yang di-upload
        if ($request->hasFile('foto_sim')) {
            // Simpan foto pada folder public dengan nama asli
            $sim = $request->file('foto_sim');
            $filename_sim = date('YmdHis') . '_' . $sim->getClientOriginalName();
            $sim->storeAs('public/SIM', $filename_sim);
        }

        // Cek apakah ada foto STNK yang di-upload
        if ($request->hasFile('foto_stnk')) {
            // Simpan foto pada folder public dengan nama asli
            $stnk = $request->file('foto_stnk');
            $filename_stnk = date('YmdHis') . '_' . $stnk->getClientOriginalName();
            $stnk->storeAs('public/STNK', $filename_stnk);
        }

        // Ubah awalan no HP
        $no_hp = $request['no_hp'];
        if ($request['no_hp'][0] == "0") {
            $no_hp = substr($no_hp, 1);
        }
        if ($no_hp[0] == "8") {
            $no_hp = "62" . $no_hp;
        }

        // Cek Email
        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email already exists',
                'status' => 'failed',
            ], 409);
        }

        // Cek no HP
        if (User::where('no_hp', $no_hp)->first()) {
            return response([
                'message' => 'No HP already exists',
                'status' => 'failed',
            ], 409);
        }

        // Buat/Simpan di Database User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $no_hp,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ], 200);

        // Buat/Simpan di Database Driver
        $driver = Driver::create([
            'user_id' => $user->id,
            'photo' => $filename_photo,
            'address' =>$request->address,
            'is_smoking' =>$request->is_smoking,
            'driver_age' =>$request->driver_age,
            'no_ktp' =>$request->no_ktp,
            'foto_ktp' => $filename_ktp,
            'foto_sim' => $filename_sim,
            'foto_stnk' => $filename_stnk,
        ]);

        return response([
            'message' => 'Registrasi Sopir Berhasil',
            'status' => 'success',
            'data' => $user . $driver
        ], 201);

    }

    public function update(Request $request, $id)
    {
        // melakukan validasi inputan pada request
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'no_hp' => 'required',
        ]);

        $user = User::with('driver')->findOrFail($id);
        dd($user);

        // Cek apakah ada foto yang di-upload
        if ($request->hasFile('photo')) {
            // Simpan foto pada folder public dengan nama asli
            $photo = $request->file('photo');
            $filename = date('YmdHis') . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/photo', $filename);

            $oldPhoto = $user->driver()->photo;
            Storage::delete('public/photo' . $oldPhoto);

            $user->driver()->photo = $filename;
        }

        // Update data pada tabel users
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        // Update data pada tabel profiles
        $user->driver()->update([
            'address' => $request->address,
            'is_smoking' => $request->is_smoking,
            'driver_age' => $request->driver_age,
            'no_ktp' => $request->no_ktp,
        ]);

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'data' => 'berhasil'
        ], 200);
    }
}
