<?php

namespace App\Http\Controllers\API\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Resources\TimeDepartureResource;
use App\Models\TimeDeparture;

class TimeDepartureController extends Controller
{
    public function index()
    {
        $timeDepartures = TimeDeparture::all();
        return TimeDepartureResource::collection($timeDepartures);

    }
}
