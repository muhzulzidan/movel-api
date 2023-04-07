<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    public function index()
    {
        $passenger = auth()->user()->passenger;

        return response([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'no_hp' => auth()->user()->no_hp,
            'alamat' => $passenger->alamat,
            'foto' => $passenger->foto,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg|max:512',
        ]);

        $user = auth()->user();

        $user->name = $request->name;
        $user->passenger->address = $request->address;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('uploads/passenger/' . $filename);
            Image::make($image->getRealPath())->resize(200, 200)->save($path);
            $passenger->foto = '/uploads/passenger/' . $filename;
        }

        $user->save();
        $user->passenger->save();

        return response([
            'message' => 'Data updated successfully',
            'status' => 'success',
        ], 200);

    }
}
