<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\User;
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
