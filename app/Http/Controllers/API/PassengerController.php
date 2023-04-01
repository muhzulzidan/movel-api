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
}
