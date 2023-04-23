<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'no_hp' => ['required', 'string', 'max:13'],
            'password' => ['required', 'confirmed', 'min:6'],
            'role_id' => ['required'],
        ]);

        $no_hp = $request['no_hp'];
        if ($request['no_hp'][0] == "0") {
            $no_hp = substr($no_hp, 1);
        }
        if ($no_hp[0] == "8") {
            $no_hp = "62" . $no_hp;
        }

        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email already exists',
                'status' => 'failed',
            ], 409);
        }

        if (User::where('no_hp', $no_hp)->first()) {
            return response([
                'message' => 'No HP already exists',
                'status' => 'failed',
            ], 409);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $no_hp,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ], 200);

        if ($request->role_id == 2) {
            $user->passenger()->create();
        } elseif ($request->role_id == 3) {
            $user->driver()->create();
        }

        return response([
            'message' => 'Registration Success',
            'status' => 'success',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->role_id == 2) {
                $request->session()->put('user_id', $user->id);
                $request->session()->flush();
                $token = $user->createToken($request->email)->plainTextToken;

                return response([
                    'token' => $token,
                    'message' => 'Login Success as Passenger',
                    'status' => 'success',
                ], 200);
            } else if ($user->role_id == 3) {
                $request->session()->put('user_id', $user->id);
                $request->session()->flush();
                $token = $user->createToken($request->email)->plainTextToken;

                return response([
                    'token' => $token,
                    'message' => 'Login Success as Driver',
                    'status' => 'success',
                ], 200);
            } else if ($user->role_id == 1) {
                $request->session()->put('user_id', $user->id);
                $request->session()->flush();
                $token = $user->createToken($request->email)->plainTextToken;

                return response([
                    'token' => $token,
                    'message' => 'Login Success as Admin',
                    'status' => 'success',
                ], 200);
            } else {
                return response([
                    'message' => 'Invalid Role',
                    'status' => 'failed',
                ], 422);
            }
        } else {
            return response([
                'message' => 'Invalid email or password',
                'status' => 'failed',
            ], 401);
        }

        return response([
            'message' => 'The Provided Credentials are incorrect',
            'status' => 'failed',
        ], 401);
    }

    public function logged_user()
    {
        $loggeduser = auth()->user();
        return response([
            'user' => $loggeduser,
            'message' => 'Logged User Data',
            'status' => 'success',
        ], 200);
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        $loggeduser = auth()->user();

        if (!Hash::check($request->old_password, $loggeduser->password)) {
            return response([
                'message' => 'Old Password is Incorrect',
                'status' => 'failed',
            ], 401);
        }

        $loggeduser->update([
            'password' => Hash::make($request->password),
        ]);

        $loggeduser->tokens()->delete();

        return response([
            'message' => 'Password Changed Successfully',
            'status' => 'success',
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->put('user_id', null);
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Logout Success',
            'status' => 'success',
        ], 200);
    }
}
