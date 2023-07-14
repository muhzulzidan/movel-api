<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenumpangController extends Controller
{
    public function index()
    {
        $passengers = User::join('passengers', 'users.id', '=', 'passengers.user_id')
            ->where('users.role_id', 2)
            ->select('users.*', 'passengers.*')
            ->get();
        return view('admin.management.penumpang.penumpang', ['passengers' => $passengers]);
    }

    public function update_view($id) {
        $show_penumpang = User::join('passengers', 'users.id', '=', 'passengers.user_id')
            ->where('passengers.id', $id)
            ->select('users.*', 'passengers.*')
            ->first();

        return view('admin.management.penumpang.edit_penumpang', compact('show_penumpang'));
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
            'gender' => ['required'],
            'age_passenger' => ['required'],
        ]);

        // Cari data pengemudi
        $passengers = Passenger::find($id);

        if ($passengers) {
            // Cari data user terkait
            $user = User::find($passengers->user_id);

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
                return redirect()->route('penumpang.update', $passengers->id);
            }

            // Update data user
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->no_hp = $validatedData['no_hp'];

            $user->save();

            // Update data pengemudi
            $passengers->address = $validatedData['address'];
            $passengers->gender = $validatedData['gender'];
            $passengers->age_passenger = $validatedData['age_passenger'];

            // Periksa apakah ada file foto yang diupload
            if ($request->hasFile('photo')) {
                // Hapus file foto lama jika ada
                if ($passengers->photo) {
                    Storage::delete($passengers->photo);
                }

                // Upload file foto baru
                $photoPath = $request->file('photo')->store('public/photo');
                $passengers->photo = $photoPath;
            }

            $passengers->save();

            return redirect()->route('penumpang.edit', $passengers->id)->with('success', 'Data Penumpang berhasil diperbarui.');
        }

        return redirect()->route('penumpang.edit', $passengers->id)->with('error', 'Data Penumpang tidak ditemukan.');
    }

    public function destroy($id)
    {
        // Ambil data pengemudi berdasarkan ID
        $passengers = Passenger::findOrFail($id);

        // Hapus file gambar dari storage
        Storage::delete([
            $passengers->photo,
        ]);

        // Hapus data pengemudi
        $passengers->delete();

        // Hapus data pengguna terkait jika tidak ada pengemudi lain yang terhubung dengannya
        $user = User::find($passengers->user_id);
        if ($user && $user->passenger()->where('id', '!=', $passengers->id)->count() === 0) {
            $user->delete();
        }
        return redirect()->route('penumpang')->with('success', 'Penumpang berhasil di-HAPUS');
    }
}
