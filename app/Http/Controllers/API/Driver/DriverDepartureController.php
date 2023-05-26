<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;

class DriverDepartureController extends Controller
{
    public function setDriverInactive()
    {
        $user = auth()->user();
        $driverDeparture = $user->driver->driver_departures->first();
        $driverDeparture->update([
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Driver tidak aktif.',
        ]);
    }
}
