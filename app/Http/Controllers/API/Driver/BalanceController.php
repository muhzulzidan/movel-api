<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balance;
use App\Models\Driver;

class BalanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $driver = Driver::where('user_id', $user->id)->first();
        $balance = Balance::where('driver_id', $driver->id)->first();

        return response($balance, 200);
    }
}
