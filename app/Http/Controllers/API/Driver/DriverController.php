<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $drivers = User::join('drivers', 'drivers.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', 'users.no_hp', 'drivers.address', 'is_smoking', 'driver_age', 'drivers.photo', 'drivers.no_ktp', 'drivers.foto_ktp', 'drivers.foto_sim', 'drivers.foto_stnk')
            ->where('users.id', '=', $user->id)
            ->get();

        return response($drivers, 200);
    }

    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_smoking' => 'required',
            'driver_age' => 'required',
        ]);

        $user = auth()->user();
        $user_id = User::join('drivers', 'drivers.user_id', '=', 'users.id')
            ->select('drivers.id as drive_id')
            ->where('users.id', $user->id)
            ->get();
        $driver_id = $user_id[0]["drive_id"];

        $input = $request->all();

        $user = User::findOrFail($user->id);
        $user->name = $request->input('name');
        $user->save();

        $driver = Driver::findOrFail($driver_id);
        // Handle file upload
        if ($request->hasFile('photo')) {
            //upload image
            $file = $request->file('photo');
            $filename = date('YmdHis') . '_' . $file->getClientOriginalName();
            Storage::putFile('public', $filename);
            //delete old image
            $oldPhoto = $driver->photo;
            Storage::delete('public/' . $oldPhoto);

            $driver->photo = $filename;
        } else {
            unset($input['photo']);
        }

        $driver->address = $request->input('address');
        $driver->is_smoking = $request->input('is_smoking');
        $driver->driver_age = $request->input('driver_age');
        $driver->save();

        return response([
            'name' => $user->name,
            'address' => $driver->address,
            'photo' => $driver->photo,
            'is_smoking' => $driver->is_smoking,
            'driver_age' => $driver->driver_age,
            'message' => 'Data updated successfully',
        ], 200);
    }
}
