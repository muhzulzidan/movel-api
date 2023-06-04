<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\User;
use GuzzleHttp\Exception\RequestException;

class LoginController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function loginVerify(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // Kirim permintaan autentikasi ke API eksternal
        $client = new Client();
        try {
            $response = $client->post('https://api.movel.id/api/user/login', [
                'form_params' => [
                    'email' => $email,
                    'password' => $password,
                ],
            ]);

            // Periksa respons yang diterima
            $data = json_decode($response->getBody(), true);

            if ($data['status']) {
                // Jika autentikasi berhasil, simpan token autentikasi ke database lokal
                $user = User::where('email', $data['email'])->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'role_id' => $data['role_id']
                    ]);
                }
                $user->token = $data['token'];
                $user->save();

                Auth::login($user);

                session()->flash('success', 'You are logged in! Welcome back ' . $data['name']);
                return redirect()->route('home');
            } else {
                // Jika autentikasi gagal, tampilkan pesan error
                return redirect()->back()->withErrors(['login' => 'Email atau password salah']);
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $data = json_decode($response->getBody(), true);
                // Tampilkan pesan kesalahan
                return redirect()->back()->withErrors(['login' => $data['message']]);
            }
        }
    }

    public function logout(Request $request)
    {
        // Ambil token autentikasi pengguna
        $token = $request->user()->token;

        // Kirim permintaan logout ke API eksternal
        $client = new Client();
        $client->post('https://api.movel.id/api/user/logout', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        // Hapus token autentikasi pengguna dari database lokal
        $request->user()->token = null;
        $request->user()->save();

        // Logout dari aplikasi Admin
        Auth::logout();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout!');
    }
}
