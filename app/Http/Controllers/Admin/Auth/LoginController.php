<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginForm()
    {
        return view('admin.auth.login');
    }

    public function loginVerify(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role_id' => 1])) {
            session()->flash('success', 'You are logged in! Welcome back ');
            return redirect()->intended($this->redirectTo);
        }

        $errorMsg = 'Invalid email or password';
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser && $existingUser->role_id !== 1) {
            $errorMsg = 'Anda bukan Admin, Anda tidak dapat mengakses Dashboard ini!';
        }


        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => $errorMsg,
        ]);

        // return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        // Logout dari aplikasi Admin
        Auth::logout();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout!');
    }
}
