<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $passenger = User::join('drivers', 'drivers.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', 'users.no_hp', 'drivers.address', 'drivers.photo', 'drivers.no_ktp', 'drivers.foto_ktp', 'drivers.foto_sim', 'drivers.foto_stnk')
            ->where('users.id', '=', $user->id)
            ->get();

        return response($passenger, 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = auth()->user();
        $user_id = User::join('drivers', 'drivers.user_id', '=', 'users.id')
            ->select('drivers.id as pass_id')
            ->where('users.id', '=', $user->id)
            ->get();
        $driver_id = $user_id[0]["pass_id"];

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
            $path = $file->storeAs('driver/profile/', $filename);
            //delete old image
            $oldPhoto = $driver->photo;
            Storage::delete('driver/profile/'.$oldPhoto);

            $driver->photo = $filename;
        }else{
            unset($input['photo']);
        }

        $driver->address = $request->input('address');
        $driver->save();

        return response([
            'name' => $user->name,
            'address' => $driver->address,
            'photo' => $driver->photo,
            'message' => 'Data updated successfully',
        ], 200);
    }
}
