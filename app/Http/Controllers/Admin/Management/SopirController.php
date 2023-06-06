<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'photo' => ['required', 'image', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'is_smoking' => ['required'],
            'driver_age' => ['required'],
            'no_ktp' => ['required'],
            'foto_ktp' => ['required', 'image', 'max:2048'],
            'foto_sim' => ['required', 'image', 'max:2048'],
            'foto_stnk' => ['required', 'image', 'max:2048'],
        ]);

        // Ubah awalan no HP
        $no_hp = $validatedData['no_hp'];
        if ($validatedData['no_hp'][0] == "0") {
            $no_hp = substr($no_hp, 1);
        }
        if ($no_hp[0] == "8") {
            $no_hp = "62" . $no_hp;
        }

        // Cek Email
        if (User::where('email', $validatedData['email'])->first()) {
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
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'email_verified_at' => date('Y-m-d H:i:s'),
            'no_hp' => $validatedData['no_hp'],
            'password' => Hash::make($validatedData['password']),
            'role_id' => 3,
        ]);

        // Simpan data driver
        $driver = Driver::create([
            'user_id' => $user->id,
            'address' => $validatedData['address'],
            'is_smoking' => $validatedData['is_smoking'],
            'driver_age' => $validatedData['driver_age'],
            'no_ktp' => $validatedData['no_ktp'],
        ]);

        // Check file sizes
        $photoSize = $request->file('photo')->getSize();
        $ktpSize = $request->file('foto_ktp')->getSize();
        $simSize = $request->file('foto_sim')->getSize();
        $stnkSize = $request->file('foto_stnk')->getSize();

        if ($photoSize > 2097152 || $ktpSize > 2097152 || $simSize > 2097152 || $stnkSize > 2097152) {
            return redirect()->back()->with('error', 'One or more files exceed the maximum file size of 2 MB.');
        }

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

    public function updateView($id)
    {
        $show_sopir = User::join('drivers', 'users.id', '=', 'drivers.user_id')
            ->where('drivers.id', $id)
            ->select('users.*', 'drivers.*')
            ->first();

        return view('admin.management.sopir.edit_sopir', compact('show_sopir'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'no_hp' => ['required', 'string', 'max:13'],
            'photo' => ['image', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'is_smoking' => ['required'],
            'driver_age' => ['required'],
            'no_ktp' => ['required'],
            'foto_ktp' => ['image', 'max:2048'],
            'foto_sim' => ['image', 'max:2048'],
            'foto_stnk' => ['image', 'max:2048'],
        ]);

        // Cari data pengemudi
        $driver = Driver::find($id);

        if ($driver) {
            // Cari data user terkait
            $user = User::find($driver->user_id);

            // Ubah awalan no HP
            $no_hp = $validatedData['no_hp'];
            if ($validatedData['no_hp'][0] == "0") {
                $no_hp = substr($no_hp, 1);
            }
            if ($no_hp[0] == "8") {
                $no_hp = "62" . $no_hp;
            }

            // Cek Email
            $userWithSameEmail = User::where('email', $validatedData['email'])->first();
            if ($userWithSameEmail && $userWithSameEmail->id !== $user->id) {
                session()->flash('error', 'Email sudah digunakan oleh pengguna lain!');
                return redirect()->route('sopir.update', $driver->id);
            }

            // Update data user
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->no_hp = $validatedData['no_hp'];

            $user->save();

            // Update data pengemudi
            $driver->address = $validatedData['address'];
            $driver->is_smoking = $validatedData['is_smoking'];
            $driver->driver_age = $validatedData['driver_age'];
            $driver->no_ktp = $validatedData['no_ktp'];

            // Periksa apakah ada file foto yang diupload
            if ($request->hasFile('photo')) {
                // Hapus file foto lama jika ada
                if ($driver->photo) {
                    Storage::delete($driver->photo);
                }

                // Upload file foto baru
                $photoPath = $request->file('photo')->store('public/photo');
                $driver->photo = $photoPath;
            }

            // Periksa apakah ada file KTP yang diupload
            if ($request->hasFile('foto_ktp')) {
                // Hapus file foto lama jika ada
                if ($driver->foto_ktp) {
                    Storage::delete($driver->foto_ktp);
                }

                // Upload file KTP baru
                $ktpPath = $request->file('foto_ktp')->store('public/KTP');
                $driver->foto_ktp = $ktpPath;
            }

            // Periksa apakah ada file SIM yang diupload
            if ($request->hasFile('foto_sim')) {
                // Hapus file foto lama jika ada
                if ($driver->foto_sim) {
                    Storage::delete($driver->foto_sim);
                }

                // Upload file SIM baru
                $simPath = $request->file('foto_sim')->store('public/SIM');
                $driver->foto_sim = $simPath;
            }

            // Periksa apakah ada file STNK yang diupload
            if ($request->hasFile('foto_stnk')) {
                // Hapus file foto lama jika ada
                if ($driver->foto_stnk) {
                    Storage::delete($driver->foto_stnk);
                }

                // Upload file STNK baru
                $stnkPath = $request->file('foto_stnk')->store('public/STNK');
                $driver->foto_stnk = $stnkPath;
            }

            $driver->save();

            return redirect()->route('sopir.update', $driver->id)->with('success', 'Data pengemudi berhasil diperbarui.');
        }

        return redirect()->route('sopir.update', $driver->id)->with('error', 'Data pengemudi tidak ditemukan.');
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
        return redirect()->route('sopir')->with('success', 'Sopir berhasil di-HAPUS');
    }
}
