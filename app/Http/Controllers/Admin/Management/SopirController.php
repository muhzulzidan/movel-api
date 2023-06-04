<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;



class SopirController extends Controller
{
    public function index()
    {
        $drivers = User::join('drivers', 'users.id', '=', 'drivers.user_id')
                     ->select('users.*', 'drivers.*')
                     ->get();
        return view('admin.management.sopir.sopir', ['drivers' => $drivers]);
    }

    public function show($id)
    {
        $show_sopir = User::join('drivers', 'users.id', '=', 'drivers.user_id')
            ->where('drivers.id', $id)
            ->select('users.*', 'drivers.*')
            ->first();

        // $show_sopir = Storage::url($driver->photo);

        return view('admin.management.sopir.show_sopir', compact('show_sopir'));
    }

    public function storeView()
    {
        return view('admin.management.sopir.add_sopir');
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
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

        // dd($validatedData);

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

        // Simpan data user
        $user = User::create([
            'name'              => $validatedData['name'],
            'email'             => $validatedData['email'],
            'email_verified_at' => date('Y-m-d H:i:s'),
            'no_hp'             => $validatedData['no_hp'],
            'password'          => Hash::make($validatedData['password']),
            'role_id'           => 3,
        ]);

        // Simpan data driver
        $driver = Driver::create([
            'user_id'       => $user->id,
            'address'       => $validatedData['address'],
            'is_smoking'    => $validatedData['is_smoking'],
            'driver_age'    => $validatedData['driver_age'],
            'no_ktp'        => $validatedData['no_ktp'],
        ]);

        // Simpan file gambar
        $photoPath = $request->file('photo')->store('public/photo');
        $ktpPath = $request->file('foto_ktp')->store('public/KTP');
        $simPath = $request->file('foto_sim')->store('public/SIM');
        $stnkPath = $request->file('foto_stnk')->store('public/STNK');

        // Mengupdate path gambar pada driver
        $driver->update([
            'photo' => $photoPath,
            'foto_ktp' => $ktpPath,
            'foto_sim' => $simPath,
            'foto_stnk' => $stnkPath,
        ]);

        // Redirect atau melakukan tindakan lainnya
        return redirect()->route('sopir')->with('success', 'Sopir baru berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'name' => 'required',
            'driver_license' => 'required',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Mengupdate data pada tabel users
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->save();

        // Mengupdate data pada tabel drivers
        $driver = Driver::where('user_id', $id)->first();
        $driver->driver_license = $request->input('driver_license');
        $driver->save();

        // Mengunggah dan menyimpan gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images', $filename);
                // Menyimpan informasi gambar ke tabel images (asumsikan tabel images telah ada dengan kolom user_id dan filename)
                $user->images()->create([
                    'filename' => $filename
                ]);
            }
        }

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    public function editSopir($id)
    {
        return view('management.sopir.edit_sopir');
    }

    public function destroy($id)
    {
        // Ambil data pengemudi berdasarkan ID
        $driver = Driver::findOrFail($id);

        // Hapus file gambar dari storage
        Storage::delete([
            $driver->photo,
            $driver->foto_ktp,
            $driver->foto_sim,
            $driver->foto_stnk,
        ]);

        // Hapus data pengemudi
        $driver->delete();

        // Hapus data pengguna terkait jika tidak ada pengemudi lain yang terhubung dengannya
        $user = User::find($driver->user_id);
        if ($user && $user->drivers()->where('id', '!=', $driver->id)->count() === 0) {
            $user->delete();
        }
        return redirect()->route('sopir')->with('success', 'Sopir berhasil dihapus');
    }
}
