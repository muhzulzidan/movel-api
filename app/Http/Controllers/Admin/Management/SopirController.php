<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Car;
use App\Models\LabelSeatCar;
use App\Models\Driver;
use App\Models\DriverDeparture;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SopirController extends Controller
{
    public function index()
    {
        $drivers = User::join('drivers', 'users.id', '=', 'drivers.user_id')
            ->join('cars', 'drivers.id', '=', 'cars.driver_id')
            // ->join('driver_departures', 'drivers.id', '=', 'driver_departures.driver_id')
            ->leftJoin('balance', 'drivers.id', '=', 'balance.driver_id')
            ->select('drivers.id as sopir_id', 'users.*', 'drivers.*', 'cars.*', 'balance.*')
            ->get();

        $balances = Balance::all();
        $allDriver = Driver::count();

        $driver_departure = DriverDeparture::all();
        $driver_aktif = DriverDeparture::where('is_active', 1)->count();

        return view('admin.management.sopir.sopir', compact('drivers', 'allDriver', 'driver_aktif', 'balances', 'driver_departure'));
    }

    public function show_view($id)
    {
        $show_sopir = User::join('drivers', 'users.id', '=', 'drivers.user_id')
            ->join('cars', 'drivers.id', '=', 'cars.driver_id')
            ->where('drivers.id', $id)
            ->select('users.*', 'drivers.*', 'cars.*')
            ->first();

        $total_penumpang = DB::table('orders')
            ->whereExists(function ($query) use ($id) {
                $query->select(DB::raw(1))
                    ->from('driver_departures')
                    ->whereColumn('driver_departures.id', '=', 'orders.driver_departure_id')
                    ->where('driver_departures.driver_id', $id);
            })
            ->count();

        return view('admin.management.sopir.detail_sopir', compact('show_sopir', 'total_penumpang'));
    }

    public function store_view()
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
            'password' => ['required', 'confirmed', 'min:8'],
            'photo' => ['required', 'image', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'is_smoking' => ['required'],
            'driver_age' => ['required'],
            'no_ktp' => ['required'],
            'foto_ktp' => ['required', 'image', 'max:2048'],
            'foto_sim' => ['required', 'image', 'max:2048'],
            'foto_stnk' => ['required', 'image', 'max:2048'],
            'merk' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'production_year' => ['required'],
            'isi_silinder' => ['required'],
            'license_plate_number' => ['required', 'string', 'max:255'],
            'machine_number' => ['required'],
            'seating_capacity' => ['required'],
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
            return redirect()->back()->withErrors(['error' => 'Email already exists']);
        }

        // dd($no_hp);
        // Cek no HP
        if (User::where('no_hp', $no_hp)->first()) {
            return redirect()->back()->withErrors(['error' => 'No HP already exists']);
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

        if (!$user) {
            return redirect()->back()->withErrors('error', 'Failed to create user');
        }

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
            return redirect()->back()->withErrors('error', 'One or more files exceed the maximum file size of 2 MB.');
        }

        // Simpan file gambar
        $photoPath = $request->file('photo')->store('public/photo');
        $ktpPath = $request->file('foto_ktp')->store('public/KTP');
        $simPath = $request->file('foto_sim')->store('public/SIM');
        $stnkPath = $request->file('foto_stnk')->store('public/STNK');

        // Save uploaded files to temporary session
        if ($request->hasFile('photo')) {
            $request->session()->put('photo', $request->file('photo')->getPathName());
        }
        if ($request->hasFile('foto_ktp')) {
            $request->session()->put('foto_ktp', $request->file('foto_ktp')->getPathName());
        }
        if ($request->hasFile('foto_sim')) {
            $request->session()->put('foto_sim', $request->file('foto_sim')->getPathName());
        }
        if ($request->hasFile('foto_stnk')) {
            $request->session()->put('foto_stnk', $request->file('foto_stnk')->getPathName());
        }

        // Mengupdate path gambar pada driver
        $driver->update([
            'photo' => $photoPath,
            'foto_ktp' => $ktpPath,
            'foto_sim' => $simPath,
            'foto_stnk' => $stnkPath,
        ]);

        if (!$driver) {
            // Delete the user that was created
            $user->delete();

            return redirect()->back()->withErrors('error', 'Failed to create driver');
        }

        // Simpan data Mobil
        $car = Car::create([
            'merk' => $validatedData['merk'],
            'type' => $validatedData['type'],
            'jenis' => $validatedData['jenis'],
            'model' => $validatedData['model'],
            'production_year' => $validatedData['production_year'],
            'isi_silinder' => $validatedData['isi_silinder'],
            'license_plate_number' => $validatedData['license_plate_number'],
            'machine_number' => $validatedData['machine_number'],
            'seating_capacity' => $validatedData['seating_capacity'],
            'driver_id' => $driver->id,
        ]);

        $seatingCapacity = $validatedData['seating_capacity'];

        if ($seatingCapacity > 0) {
            $labelSeats = ['Sopir'];

            // Membuat array abjad mulai dari A
            $alphabet = range('A', 'Z');

            for ($i = 0; $i < $seatingCapacity - 1; $i++) {
                if (isset($alphabet[$i])) {
                    $labelSeats[] = $alphabet[$i];
                } else {
                    // Jika melebihi Z, gunakan A lagi dengan penanda ganda (AA, AB, AC, ...)
                    $index = $i - count($alphabet);
                    $labelSeats[] = $alphabet[$index] . $alphabet[0];
                }
            }

            // Simpan data ke tabel label_seat_cars
            foreach ($labelSeats as $label) {
                $isFilled = $label == 'Sopir' ? 1 : 0;

                LabelSeatCar::create([
                    'label_seat' => $label,
                    'is_filled' => $isFilled,
                    'car_id' => $car->id,
                ]);
            }
        }

        // Buat tabel Balance
        Balance::create([
            'driver_id' => $driver->id,
            'saldo' => 0,
        ]);

        if (!$car) {
            // Delete the user and driver that were created
            $driver->delete();
            $user->delete();
            $driver->balance()->delete();

            return redirect()->back()->withErrors('error', 'Failed to create car');
        }

        // Redirect atau melakukan tindakan lainnya
        return redirect()->route('sopir')->with('success', 'Sopir baru berhasil ditambahkan');
    }

    public function update_view($id)
    {
        $show_sopir = User::join('drivers', 'users.id', '=', 'drivers.user_id')
            ->join('cars', 'drivers.id', '=', 'cars.driver_id')
            ->where('drivers.id', $id)
            ->select('users.*', 'drivers.*', 'cars.*')
            ->first();

        return view('admin.management.sopir.edit_sopir', compact('show_sopir'));
    }

    public function update_driver(Request $request, $id)
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

            return redirect()->route('sopir.edit', $driver->id)->with('success', 'Data pengemudi berhasil diperbarui.');
        }

        return redirect()->route('sopir.edit', $driver->id)->withErrors('error', 'Data pengemudi tidak ditemukan.');
    }

    public function update_car(Request $request, $id)
    {
        // Validasi data yang diterima untuk tabel cars
        $validatedData = $request->validate([
            'merk' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'production_year' => ['required'],
            'isi_silinder' => ['required'],
            'license_plate_number' => ['required', 'string', 'max:255'],
            'machine_number' => ['required'],
            'seating_capacity' => ['required'],
        ]);

        // Update data pada tabel cars berdasarkan ID
        $car = Car::findOrFail($id);
        $car->update($validatedData);

        // Update juga tabel label_seat_cars
        $seatingCapacity = $validatedData['seating_capacity'];
        $labelSeats = ['Sopir'];

        // Membuat array abjad mulai dari A
        $alphabet = range('A', 'Z');

        for ($i = 0; $i < $seatingCapacity - 1; $i++) {
            if (isset($alphabet[$i])) {
                $labelSeats[] = $alphabet[$i];
            } else {
                // Jika melebihi Z, gunakan A lagi dengan penanda ganda (AA, AB, AC, ...)
                $index = $i - count($alphabet);
                $labelSeats[] = $alphabet[$index] . $alphabet[0];
            }
        }

        // Hapus data yang tidak diperlukan jika seating_capacity berkurang
        $labelSeatsToDelete = LabelSeatCar::where('car_id', $car->id)
            ->whereNotIn('label_seat', $labelSeats)
            ->get();

        foreach ($labelSeatsToDelete as $labelSeatToDelete) {
            $labelSeatToDelete->delete();
        }

        // Tambahkan data baru jika seating_capacity bertambah
        $existingLabelSeats = LabelSeatCar::where('car_id', $car->id)->pluck('label_seat')->toArray();
        $labelSeatsToAdd = array_diff($labelSeats, $existingLabelSeats);

        foreach ($labelSeatsToAdd as $label) {
            $isFilled = $label == 'Sopir' ? 1 : 0;

            LabelSeatCar::create([
                'label_seat' => $label,
                'is_filled' => $isFilled,
                'car_id' => $car->id,
            ]);
        }

        // Redirect atau melakukan tindakan lainnya
        return redirect()->route('sopir.edit', $car->driver_id)->with('success', 'Data Mobil berhasil diperbarui');
    }

    public function topup(Request $request, $id)
    {
        $validatedData = $request->validate([
            'saldo' => ['required'],
        ]);

        $balance = Balance::where('driver_id', $id)->first();

        if ($balance) {

            // Jika data sudah ada, update saldo
            $balance->driver_id = $id;
            $balance->saldo += $validatedData['saldo'];

            $balance->save();
        } else {
            // Jika data belum ada, buat data baru
            $balance = Balance::create([
                'driver_id' => $id,
                'saldo' => $validatedData['saldo'],
            ]);
        }

        return redirect()->route('sopir', $id)->with('success', 'TopUp berhasil');
    }

    public function changeSaldo(Request $request, $id)
    {
        $validatedData = $request->validate([
            'saldo' => ['required'],
        ]);

        $balance = Balance::where('driver_id', $id)->first();

        // Jika data sudah ada, update saldo
        $balance->driver_id = $id;
        $balance->saldo = $validatedData['saldo'];

        $balance->save();

        return redirect()->route('sopir', $id)->with('success', 'TopUp berhasil');
    }

    public function destroy($id)
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Ambil data pengemudi berdasarkan ID
        $driver = Driver::findOrFail($id);

        // Hapus file gambar dari storage
        Storage::delete([
            $driver->photo,
            $driver->foto_ktp,
            $driver->foto_sim,
            $driver->foto_stnk,
        ]);

        // Hapus data pengguna terkait jika tidak ada pengemudi lain yang terhubung dengannya
        $user = User::find($driver->user_id);
        if ($user && $user->drivers()->where('id', '!=', $driver->id)->count() === 0) {
            $user->delete();
        }

        // Hapus data saldo terkait
        $balance = Balance::where('driver_id', $id)->first();
        if ($balance) {
            $balance->delete();
        }

        // Hapus data mobil terkait
        $car = Car::where('driver_id', $id)->first();
        if ($car) {
            // Hapus data label_seat_cars terkait
            LabelSeatCar::where('car_id', $car->id)->delete();

            // Hapus data mobil
            $car->delete();
        }

        // Hapus data pengemudi
        $driver->delete();

        // Enable foreign key checks again
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return redirect()->route('sopir')->with('success', 'Sopir berhasil di-HAPUS');
    }
}
