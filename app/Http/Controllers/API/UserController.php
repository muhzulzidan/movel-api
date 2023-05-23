<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Fungsi untuk register Passenger
    public function registerPassenger(Request $request)
    {
        // Validasi inputan register
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'no_hp' => ['required', 'string', 'max:13'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        // Konversi otomatis nomor +62
        $no_hp = $request['no_hp'];
        if ($request['no_hp'][0] == "0") {
            $no_hp = substr($no_hp, 1);
        }
        if ($no_hp[0] == "8") {
            $no_hp = "62" . $no_hp;
        }

        // Jika nomor HP sudah ada
        if (User::where('no_hp', $no_hp)->first()) {
            return response([
                'success' => false,
                'message' => 'No HP has been taken',
            ], 422);
        }

        // Buat akun user Passenger
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $no_hp,
            'password' => Hash::make($request->password),
            'role_id' => 2,
        ]);

        // Buat data Passenger
        $user->passenger()->create();

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        // Respon berhasil registrasi
        return response()->json([
            'success' => true,
            'message' => 'Kami telah mengirimkan kode verifikasi untuk registrasi melalui akun email. Silahkan cek email kamu!',
        ], 200);
    }

    // Fungsi verifikasi email
    public function verify($id, Request $request)
    {
        // Jika validasi gagal
        if (!$request->hasValidSignature()) {
            return response()->json([
                'status' => false,
                'message' => 'Verifying email fails',
            ], 400);
        }

        $user = User::find($id);

        // Jika email belum diverifikasi maka dilakukan verifikasi
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        } else {
            // Informasi email telah terverifikasi sebelumnya diarahkan kesini
            return redirect()->to('/email-verified');
        }

        // Informasi email berhasil diverifikasi diarahkan kesini
        return redirect()->to('/email-verify');
    }

    // Fungsi untuk login ke sistem
    public function login(Request $request)
    {
        // Validasi inputan email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mengambil data user/pengguna berdasarkan email yang diinput
        $user = User::where('email', $request->email)->first();

        // Jika pengguna berdasarkan email tidak ada return error 400
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak tersedia',
            ], 400);
        }

        // Jika email belum terverifikasi ada return error 403
        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal login! Email belum terverifikasi',
            ], 403);
        }

        // Jika pengguna berdasarkan email ada dan password benar
        if (Hash::check($request->password, $user->password)) {

            // Buat token untuk user tersebut
            $token = $user->createToken($request->email)->plainTextToken;

            // response berhasil masuk
            return response()->json([
                'success' => true,
                'message' => 'Berhasil masuk!',
                'data' => [
                    'id' => $user->id,
                    'token' => $token,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'role_name' => $user->role->role_name,
                ],
            ]);
        }

        // Jika pengguna berdasarkan email ada tapi password salah
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah!',
        ], 403);
    }

    // public function change_password(Request $request)
    // {
    //     $request->validate([
    //         'old_password' => 'required',
    //         'password' => 'required|confirmed',
    //     ]);

    //     $userdata = auth()->user();

    //     if (!Hash::check($request->old_password, $userdata->password)) {
    //         return response([
    //             'message' => 'Old Password is Incorrect',
    //             'status' => 'failed',
    //         ], 401);
    //     }

    //     $userdata->update([
    //         'password' => Hash::make($request->password),
    //     ]);

    //     $userdata->tokens()->delete();

    //     return response([
    //         'message' => 'Password Changed Successfully',
    //         'status' => 'success',
    //     ], 200);
    // }

    // Fungsi logout dari sistem
    public function logout(Request $request)
    {
        // Menghapus semua token terkait user yang login
        $request->user()->tokens()->delete();

        // Response berhasil logout
        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout',
        ]);
    }
}
