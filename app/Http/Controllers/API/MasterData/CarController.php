<?php

namespace App\Http\Controllers\API\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarDetailResource;
use App\Http\Resources\DriverSeatCarResource;
use App\Http\Resources\LabelSeatCarResource;

class CarController extends Controller
{

    public function resetSeatCar()
    {
        $user = auth()->user();
        $car = $user->driver->car;

        foreach ($car->labelSeats as $seat) {
            if ($seat->label_seat !== 'Sopir') {
                $seat->is_filled = 0;
                $seat->save();
            }
        }

        return response()->json(['message' => 'Car seats reset successfully']);
    }
    
    public function getCar()
    {
        $user = auth()->user();
        $car = $user->driver->car;
        return new CarDetailResource($car);

    }
    public function getSeatCar()
    {
        $user = auth()->user();
        $car = $user->driver->car;
        return (new DriverSeatCarResource($car))->additional(['data' => ['car_seats' => LabelSeatCarResource::collection($car->labelSeats)]]);
    }
}
