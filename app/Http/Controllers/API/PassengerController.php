<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Passenger;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PassengerController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $passenger = DB::table('users')
            ->join('passengers', 'passengers.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', 'users.no_hp', 'passengers.address', 'passengers.photo')
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
        $user_id = User::join('passengers', 'passengers.user_id', '=', 'users.id')
            ->select('passengers.id as pass_id')
            ->where('users.id', '=', $user->id)
            ->get();
        $passenger_id = $user_id[0]["pass_id"];
  
        $input = $request->all();
        
        $user = User::findOrFail($user->id);
        $user->name = $request->input('name');
        $user->save();

        $passenger = Passenger::findOrFail($passenger_id);
        // Handle file upload
        if ($request->hasFile('photo')) {
            //upload image
            $file = $request->file('photo');
            $filename = date('YmdHis') . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('passenger/profile/', $filename);
            //delete old image
            $oldPhoto = $passenger->photo;
            Storage::delete('passenger/profile/'.$oldPhoto);
            
            $passenger->photo = $filename;
        }else{
            unset($input['photo']);
        }
        
        $passenger->address = $request->input('address');
        $passenger->save();

        return response([
            'name' => $user->name,
            'address' => $passenger->address,
            'photo' => $passenger->photo,
            'message' => 'Data updated successfully',
        ], 200);
    }
}